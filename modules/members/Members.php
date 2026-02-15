<?php


class Members extends Trongate
{

    public function log_user_in($member_obj)
    {
        block_url('members/log_user_in');

        //execute the login process
        $member_obj = $this->model->log_user_in($member_obj);

        $member_obj->target_url = ($member_obj->password === '' || $member_obj->password === null)  ? 'members/update_password' : 'dashboard';

        return $member_obj;
    }

    public function update_password()
    {
        echo 'need to add a password';
    }

    public function log_user_out()
    {
        block_url('members/log_user_out');
    }

    public function logout()
    {
        $this->trongate_tokens->destroy();
        redirect(LOGIN_URL);
    }


    public function make_sure_allowed()
    {
        $trongate_user_obj = $this->trongate_tokens->get_user_obj();
        if ($trongate_user_obj === false) {
            // No user object found, deny access
            redirect(LOGIN_URL);
        }
    }
}
