<?php


class Members extends Trongate
{

    public function make_sure_allowed()
    {
        $trongate_user_obj = $this->trongate_tokens->get_user_obj();
        if ($trongate_user_obj === false) {
            // No user object found, deny access
            redirect('members-login');
        }
    }
}
