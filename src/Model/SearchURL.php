<?php

namespace Model;

class SearchURL extends \Topdb\Table
{
    public $db_name = 'opermonitor';
    public $table_name = 'search';
    public $primary_key = 'search_id';

    public function get($sql)
    {
        $sth = $this->query($sql);
        return $sth->fetchObject();
    }

    public function getInfo($uid)
    {
        $sql = "SELECT user_name, sex, birthday, identity_card, address, license_plate_no 
FROM $this->db_name.pl_user_t 
WHERE user_id = '$uid' 
LIMIT 1";

        $user = $this->get($sql);
        if ($user && $user->birthday) {
            $time = strtotime($user->birthday);
            $user->birthday = date('Y/m/d', $time);
        }
        return $user;
    }
}
