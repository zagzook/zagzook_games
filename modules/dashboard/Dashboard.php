<?php


class Dashboard extends Trongate
{


    //code goes here...
    public function index()
    {

        //make sure the user is loged as a member before they can access the dashboard
        $this->trongate_security->make_sure_allowed('members area');

        // echo 'Private member\'s area<br><br>';

        $logout_url = BASE_URL . LOGOUT_URL;
        $token_obj = $this->trongate_tokens->get_user_obj();
        $member_obj = $this->model->get_member_obj($token_obj->trongate_user_id);
        $data = [
            'member_obj' => $member_obj,
            'member_level' => $token_obj->user_level,
            'view_module' => 'dashboard',
            'view_file' => 'dashboard',
            'logout_url' => $logout_url
        ];
        $this->templates->admin($data);
    }
}
