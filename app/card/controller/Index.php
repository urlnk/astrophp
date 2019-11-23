<?php

namespace App\Card\Controller;

class Index extends \MagicCube\Controller
{
    public function index()
    {
        $card = $user = null;
        
        if (isset($_GET['phone'])) {
            $phone = (int) $_GET['phone'];
            $oid = (int) $_GET['oid'];

            $sql = "SELECT user_id FROM yj.pl_user_t WHERE telephone = '$phone' AND operator_id = '$oid' LIMIT 1";
            $SearchURL = new \Model\SearchURL();
            $statement = $SearchURL->query($sql);
            $user = $statement->fetchObject();

            if ($user) {
                $sql = "SELECT card_code FROM yj.pl_card_t WHERE user_id = '$user->user_id' LIMIT 1";
                $statement = $SearchURL->query($sql);
                $card = $statement->fetchObject();
            }
        }

        if (!$card) {
            header("Location: /card/login");
            exit;
        }

        return array(
            'card' => $card,
            'user' => $user,
        );
    }
}
