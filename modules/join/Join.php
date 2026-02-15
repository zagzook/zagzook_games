<?php


class Join extends Trongate
{

    public function index()
    {
        $data = $this->model->get_data_from_post();
        $data['view_module'] = 'join';
        $data['view_file'] = 'join';
        $this->templates->public($data);
    }

    public function submit()
    {
        $this->validation->set_rules('username', 'username', 'required|min_length[5]|max_length[60]|callback_username_check');
        $this->validation->set_rules('first_name', 'first name', 'required|max_length[60]');
        $this->validation->set_rules('last_name', 'last name', 'required|max_length[70]');
        $this->validation->set_rules('email_address', 'email address', 'required|valid_email|callback_email_check');

        $results = $this->validation->run();

        if ($results) {

            // fetch the posted data
            $data = $this->model->get_data_from_post();

            $member_id = $this->model->create_new_member_record($data);

            // Send and activate account email (later)
            $this->send_activate_account_email($member_id);

            // Redirect the user to a 'Chek you email' page
            redirect('join/check_your_email');
        } else {
            $this->index();
        }
    }
    public function test()
    {
        $member_id = 3;
        $this->send_activate_account_email($member_id);
    }

    private function send_activate_account_email($member_id)
    {
        // Fetch the member's email address and user token and first & last name
        $member_obj = $this->model->get_member_obj($member_id);

        $first_name = $this->encryption->decrypt($member_obj->first_name);
        $last_name = $this->encryption->decrypt($member_obj->last_name);
        $email_address = $member_obj->email_address;
        $user_token = $member_obj->user_token;
        $activation_link = BASE_URL . 'join/activate_account/' . $user_token;

        $data = [
            'email_address' => $email_address,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'activation_link' => $activation_link
        ];


        $body_html = $this->view('activate_account_email', $data, true);

        // send the email.
        if (strtolower(ENV) !== 'dev') {
            // We are live and send emails for real
            $email_params = [
                'to_email' => $data['email_address'],
                'to_name' => $data['first_name'] . ' ' . $data['last_name'],
                'subject' => 'Activate your account at ' . OUR_NAME,
                'body_html' => $body_html
            ];

            $result = $this->trongate_email->send($email_params);

            if (!$result) {
                // Email failed to send. Log the error for debugging.
                echo  'Failed to send activation email to ' . $data['email_address'];
                die();
            }
        }
    }

    public function check_your_email()
    {
        $data = [
            'view_module' => 'join',
            'view_file' => 'check_your_email'
        ];

        $this->templates->public($data);
    }

    public function activate_account()
    {
        $user_token = segment(3);

        $member_obj = $this->model->attempt_activate_account($user_token);

        if (!$member_obj) {
            $data = [
                'view_module' => 'join',
                'view_file' => 'invalid_activation_token'
            ];

            $this->templates->public($data);
        } else {
            $member_obj = $this->members->log_user_in($member_obj);
            redirect($member_obj->target_url);
        }
    }

    public function username_check($username)
    {
        // make sure the user name is not already taken
        $results = $this->model->username_check($username);

        return $results;
    }

    public function email_check($email_address)
    {
        // make sure the user name is not already taken
        $results = $this->model->email_check($email_address);
        return $results;
    }
}
