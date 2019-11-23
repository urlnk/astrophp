<?php

namespace App\Card\Controller;

class _Controller extends \MagicCube\Controller
{

    public function _action()
    {
        return ['method' => __METHOD__];
    }

    public function login()
    {
        $sql = "SELECT operator_id, operator_name
FROM yj.pl_operator_info_t
WHERE operator_type = '2'
LIMIT 50";

        $SearchURL = new \Model\SearchURL();
        $statement = $SearchURL->query($sql);
        $operators = $statement->fetchAll(\PDO::FETCH_OBJ);

        return array(
            'operators' => $operators,
        );
    }

    public function account()
    {
        $uid = (int) $_GET['uid'];
        $arr = array(
            0,
            0,
            0,
            0,
        );

        $sql = "SELECT acct_type, balance FROM yj.pl_acct_balance_t WHERE user_id = '$uid' LIMIT 2";
        $SearchURL = new \Model\SearchURL();
        $statement = $SearchURL->query($sql);
        $balances = $statement->fetchAll(\PDO::FETCH_OBJ);
        foreach ($balances as $balance) {
            $arr[$balance->acct_type] = $balance->balance;
        }

        if ($uid) {
            $sql = "SELECT card_code, effective_time FROM yj.pl_card_t WHERE user_id = '$uid' LIMIT 1";
            $statement = $SearchURL->query($sql);
            $card = $statement->fetchObject();
        }

        return array(
            'card' => $card,
            'arr' => $arr,
        );
    }

    public function recharge()
    {
        $uid = (int) $_GET['uid'];
        $arr = array(
            0,
            0,
            0,
            0,
        );

        $sql = "SELECT acct_type, balance FROM yj.pl_acct_balance_t WHERE user_id = '$uid' LIMIT 2";
        $SearchURL = new \Model\SearchURL();
        $statement = $SearchURL->query($sql);
        $balances = $statement->fetchAll(\PDO::FETCH_OBJ);
        foreach ($balances as $balance) {
            $arr[$balance->acct_type] = $balance->balance;
        }

        return array(
            'arr' => $arr,
        );
    }

    public function log()
    {
        $uid = (int) $_GET['uid'];

        $sql = "SELECT order_amount, param_name, order_time 
FROM yj.pl_order_flow_t A 
LEFT JOIN yj.pl_parameter_code_t B ON B.param_type='payment_code' AND B.param_code=A.payment_code 
WHERE user_id = '$uid' AND order_type_code = 'RECHARGE' AND refund_flag = '0' 
ORDER BY order_flow_no DESC 
LIMIT 50";

        $SearchURL = new \Model\SearchURL();
        $statement = $SearchURL->query($sql);
        $payments = $statement->fetchAll(\PDO::FETCH_OBJ);

        return array(
            'payments' => $payments,
        );
    }

    public function consume()
    {
        $uid = (int) $_GET['uid'];

        $sql = "SELECT order_time, device_name, order_amount, payment_amount, balance 
FROM yj.pl_order_flow_t A  
LEFT JOIN yj.pl_payment_flow_t B ON B.order_flow_no = A.order_flow_no 
LEFT JOIN yj.pl_logical_device_t C ON C.operator_id = A.operator_id AND C.device_no = A.device_no 
WHERE user_id = '$uid' AND order_type_code = 'CONSUME' AND refund_flag = 0 
ORDER BY A.order_flow_no DESC 
LIMIT 50";

        $SearchURL = new \Model\SearchURL();
        $statement = $SearchURL->query($sql);
        $consumes = $statement->fetchAll(\PDO::FETCH_OBJ);

        return array(
            'consumes' => $consumes,
        );
    }

    public function loss()
    {
        $uid = (int) $_GET['uid'];

        $sql = "SELECT param_name, card_code 
FROM yj.pl_card_t A 
LEFT JOIN yj.pl_parameter_code_t B ON B.param_type='card_status' AND B.param_code = A.card_status 
WHERE user_id = '$uid' 
LIMIT 1";

        $SearchURL = new \Model\SearchURL();
        $statement = $SearchURL->query($sql);
        $card = $statement->fetchObject();

        return array(
            'card' => $card,
        );
    }

    public function info()
    {
        $uid = (int) $_GET['uid'];

        $sql = "SELECT user_name, sex, birthday, user_id 
FROM yj.pl_user_t 
WHERE user_id = '$uid' 
LIMIT 1";

        $SearchURL = new \Model\SearchURL();
        $statement = $SearchURL->query($sql);
        $user = $statement->fetchObject();

        return array(
            'user' => $user,
        );
    }

    public function __destruct()
    {
        global $template;
        $uriInfo =& $this->uriInfo;

        $action = $uriInfo['action'] ? : '_action';
        if ('_controller' != $uriInfo['controller']) {
            $actionCnt = lcfirst($uriInfo['controller']);
            $uriInfo['controller'] = '_controller';

            $action = in_array($actionCnt, $this->methods) ? $actionCnt : $action;
        }
        $uriInfo['action'] = $action;
        # print_r(get_defined_vars());

        parent::__destruct();
    }
}
