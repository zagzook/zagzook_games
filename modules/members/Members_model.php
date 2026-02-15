<?php

class Members_model extends Model
{
    public function log_user_in($member_obj)
    {
        // Generate a token for the user and set it in the cookie
        $this->module('trongate_tokens');

        $token_data = [
            'user_id' => (int) $member_obj->trongate_user_id,
            'expiry_date' => time() + (60 * 60 * 24 * 60), // 60 days from now
            'set_cookie' => true
        ];
        echo 'Members_model => : log_user_in';

        json($member_obj);


        $this->trongate_tokens->generate_token($token_data);

        // Update 'num_logins'

        $update_data = (int) $member_obj->id;
        $num_logins = (int) $member_obj->num_logins;
        $data['num_logins'] = $num_logins + 1;
        $this->db->update($update_data, $data, 'members');

        $member_obj->num_logins = $data['num_logins'];


        return $member_obj;
    }
}
