<?php

class Dashboard_model extends Model
{

    public function get_member_obj($token_id)
    {
        // code goes here...
        $params = [
            'trongate_user_id' => $token_id,
        ];

        $sql = 'SELECT * 
                FROM members 
                WHERE trongate_user_id = :trongate_user_id';

        $rows = $this->db->query_bind($sql, $params, 'object');

        if (empty($rows)) {
            return false;
        }

        $members_obj = $rows[0];

        $this->module('encryption');
        $members_obj->first_name = $this->encryption->decrypt($members_obj->first_name);
        $members_obj->last_name = $this->encryption->decrypt($members_obj->last_name);

        return $members_obj;
    }
}
