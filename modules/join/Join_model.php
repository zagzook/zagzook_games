<?php


class Join_model extends Model
{
    //code goes here...
    public function get_data_from_post()
    {
        $data = [
            'username' => post('username', true),
            'first_name' => post('first_name', true),
            'last_name' => post('last_name', true),
            'email_address' => post('email_address', true)
        ];
        return $data;
    }

    public function username_check($username)
    {
        // Only allow letters (a-z, A-Z) and numbers (0-9).
        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            return 'The username can only contain letters and numbers';
        }

        $user_obj = $this->db->get_one_where('username', $username, 'members');

        if ($user_obj === false) {
            // The username is available!
            return true;
        } else {
            $error_msg = 'The username that you submitted is not available.';
            return $error_msg;
        }
    }

    public function email_check($email_address)
    {
        $user_obj = $this->db->get_one_where('email_address', $email_address, 'members');

        if ($user_obj === false) {
            // The email address is available!
            return true;
        } else {
            $error_msg = 'The email address that you submitted is not available.';
            return $error_msg;
        }
    }

    public function create_new_member_record($data)
    {
        // create a record in the trongate_users table
        $tringate_user_data = [
            'code' => make_rand_str(32),
            'user_level_id' => 2
        ];

        $data['trongate_user_id'] = $this->db->insert($tringate_user_data, 'trongate_users');

        // create a record in the members table
        $this->module('encryption');
        $data['first_name'] = $this->encryption->encrypt($data['first_name']);
        $data['last_name'] = $this->encryption->encrypt($data['last_name']);
        $data['date_created'] = time();
        $data['num_logins'] = 0;
        $data['password'] = '';
        $data['user_token'] = make_rand_str(32);
        $data['confirmed'] = 0;

        $member_id = $this->db->insert($data, 'members');
        return $member_id;
    }

    public function attempt_activate_account($user_token)
    {
        $member_obj = $this->db->get_one_where('user_token', $user_token, 'members');


        if (!$member_obj) {
            // No matching user token found
            return false;
        }

        $data = [
            'confirmed' => 1,
            'user_token' => '' // Clear the token to prevent reuse
        ];

        $update_id = (int)$member_obj->id;

        $this->db->update($update_id, $data, 'members');

        return $member_obj; // will be ether an object or false


    }
}
