<?php


class Dashboard extends Trongate
{
    //code goes here...
    public function index()
    {
        //make sure the user is loged as a member before they can access the dashboard
        $this->trongate_security->make_sure_allowed('members area');

        echo 'Private member\'s area';
    }
}
