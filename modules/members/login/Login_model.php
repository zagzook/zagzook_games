<?php
class Login_model extends Model
{

    public function login_check($submitted_username, $submitted_password)
    {
        $error_msg = 'Invalid username/email and/or password';

        $members_obj = $this->attempt_find_matching_user($submitted_username);


        if (!$members_obj) {
            return $error_msg;
        }

        $stored_password = $members_obj->password;
        $password_valid = password_verify($submitted_password, $stored_password);
        if (!$password_valid) {
            return $error_msg;
        }


        return true;
    }

    public function attempt_find_matching_user($submitted_username)
    {
        $params = [
            'username' => $submitted_username,
            'email_address' => $submitted_username
        ];

        $sql = 'SELECT * 
                FROM members 
                WHERE (username = :username OR email_address = :email_address) 
                    AND confirmed = 1';

        $rows = $this->db->query_bind($sql, $params, 'object');

        if (empty($rows)) {
            return false;
        }

        $members_obj = $rows[0];

        return $members_obj;
    }
}
