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
            echo 'Your account has been created successfully. Your member ID is: ' . $member_id;
        } else {
            $this->index();
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
