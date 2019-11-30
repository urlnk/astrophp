<?php

namespace App\Ka\Controller;

class Api extends _controller
{
    public function swipe()
    {
        $no = isset($_POST['no']) ? trim($_POST['no']) : null;
        $code = 0;
        $msg = '';
        $data = array();
        $SearchURL = new \Model\SearchURL();

        if (is_numeric($no)) {
            $hex = base_convert($no, 10, 16);
            $len = strlen($hex);
            while ($len < 8) {
                $hex = '0' . $hex;
                $len++;
            }
            $front = substr($hex, 0, 4);
            $back = substr($hex, 4, 4);
            $str = $back . $front;
            $decimal = base_convert($str, 16, 10);

            $sql = "SELECT A.user_id AS user_id, user_name, telephone, operator_name, organ_name 
FROM $this->db.pl_card_t A 
LEFT JOIN $this->db.pl_user_t B ON B.user_id = A.user_id 
LEFT JOIN $this->db.pl_operator_info_t C ON C.operator_id = A.operator_id 
LEFT JOIN $this->db.pl_organization_t D ON D.organ_id = B.organ_id 
WHERE A.card_code = '$decimal' 
LIMIT 50";
            $statement = $SearchURL->query($sql);
            $data = $statement->fetchAll(\PDO::FETCH_OBJ);

        } else {
            $code = 3;
            $msg = '不是有效的卡号';
        }

        $arr = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        );
        echo json_encode($arr);
        exit;
    }
}
