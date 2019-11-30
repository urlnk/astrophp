<?php

namespace App\Card\Controller;

class _Controller extends \MagicCube\Controller
{
    public $static_version = '?v=4';

    public function __construct($vars = [])
    {
        global $_CONFIG;
        parent::__construct($vars);
        $controller = $vars['uriInfo']['controller'];

        session_start();
        $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
        if (!$uid && 'Login' != $controller) {
            header("Location: /card/login");
            exit;
        }
        $this->db = $_CONFIG['database']['db_name'];
    }

    public function _action()
    {
        return array(
            'static_version' => $this->static_version,
        );
    }

    public function logout()
    {
        session_destroy();
        header("Location: /card");
        exit;
    }

    public function login()
    {
        $oid = isset($_POST['oid']) ? $_POST['oid'] : null;
        $phone = isset($_POST['phone']) ? $_POST['phone'] : null;

        $err = null;
        $SearchURL = new \Model\SearchURL();

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if (!$phone) {
                $err = '请输入手机号';
            } else {
                $sql = "SELECT user_id, user_name FROM $this->db.pl_user_t WHERE telephone = '$phone' AND operator_id = '$oid' LIMIT 1";
                $statement = $SearchURL->query($sql);
                $user = $statement->fetchObject();
                if (!$user) {
                    $err = '在该商户没有找到这个手机号';
                } else {
                    $sql = "SELECT card_code, card_id FROM $this->db.pl_card_t WHERE user_id = '$user->user_id' LIMIT 1";
                    $statement = $SearchURL->query($sql);
                    $card = $statement->fetchObject();

                    if ($card) {
                        $_SESSION['user'] = $user;
                        $_SESSION['card'] = $card;
                        $_SESSION['uid'] = $user->user_id;
                        $_SESSION['oid'] = $oid;
                        $_SESSION['phone'] = $phone;
                        header("Location: /card");
                        exit;
                    } else {
                        $err = '无卡用户';
                    }
                }
            }
        }

        $sql = "SELECT operator_id, operator_name
FROM $this->db.pl_operator_info_t
WHERE operator_type = '2'
LIMIT 50";

        $statement = $SearchURL->query($sql);
        $operators = $statement->fetchAll(\PDO::FETCH_OBJ);

        return array(
            'operators' => $operators,
            'err' => $err,
            'phone' => $phone,
            'oid' => $oid,
            'static_version' => $this->static_version,
        );
    }

    public function account()
    {
        $uid = $_SESSION['uid'];
        $arr = array(
            0,
            0,
            0,
            0,
        );

        $sql = "SELECT acct_type, balance FROM $this->db.pl_acct_balance_t WHERE user_id = '$uid' LIMIT 2";
        $SearchURL = new \Model\SearchURL();
        $statement = $SearchURL->query($sql);
        $balances = $statement->fetchAll(\PDO::FETCH_OBJ);
        foreach ($balances as $balance) {
            $arr[$balance->acct_type] = $balance->balance;
        }

        if ($uid) {
            $sql = "SELECT card_code, effective_time FROM $this->db.pl_card_t WHERE user_id = '$uid' LIMIT 1";
            $statement = $SearchURL->query($sql);
            $card = $statement->fetchObject();
        }

        return array(
            'card' => $card,
            'arr' => $arr,
            'static_version' => $this->static_version,
        );
    }

    public function recharge()
    {
        $uid = $_SESSION['uid'];
        $arr = array(
            0,
            0,
            0,
            0,
        );

        $sql = "SELECT acct_type, balance FROM $this->db.pl_acct_balance_t WHERE user_id = '$uid' LIMIT 2";
        $SearchURL = new \Model\SearchURL();
        $statement = $SearchURL->query($sql);
        $balances = $statement->fetchAll(\PDO::FETCH_OBJ);
        foreach ($balances as $balance) {
            $arr[$balance->acct_type] = $balance->balance;
        }

        return array(
            'arr' => $arr,
            'static_version' => $this->static_version,
        );
    }

    public function log()
    {
        $uid = $_SESSION['uid'];
        $start = isset($_GET['start']) ? $_GET['start'] : null;
        $end = isset($_GET['end']) ? $_GET['end'] : null;
        $SearchURL = new \Model\SearchURL();
        $limit = 20;
        $total = $overflow = 0;

        // 筛选日期
        $filter = '';
        if (preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $start, $matches)) {
            $start_time = str_replace('/', '-', $start);
            $filter .= " AND order_time >= '$start_time 00:00:00'";
        }
        if (preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $end, $matches)) {
            $end_time = str_replace('/', '-', $end);
            $filter .= " AND order_time <= '$end_time 23:59:59'";
        }

        // 总条数
        $sql = "SELECT COUNT(1) AS total 
FROM $this->db.pl_order_flow_t A 
WHERE user_id = '$uid' AND order_type_code = 'RECHARGE' AND refund_flag = '0' $filter ";

        $sth = $SearchURL->query($sql);
        $var = $sth->fetchObject();
        if ($var) {
            $total = $var->total;
        }
        $overflow = $limit >= $total ? 1 : 0;

        // 所有条目
        $sql = "SELECT order_amount, param_name, order_time 
FROM $this->db.pl_order_flow_t A 
LEFT JOIN $this->db.pl_parameter_code_t B ON B.param_type='payment_code' AND B.param_code=A.payment_code 
WHERE user_id = '$uid' AND order_type_code = 'RECHARGE' AND refund_flag = '0' $filter 
ORDER BY order_flow_no DESC 
LIMIT $limit";

        $statement = $SearchURL->query($sql);
        $payments = $statement->fetchAll(\PDO::FETCH_OBJ);

        // 最早日期
        $sql = "SELECT order_time FROM $this->db.pl_order_flow_t WHERE user_id = '$uid' AND order_type_code = 'RECHARGE' ORDER BY order_time LIMIT 1";
        $sth = $SearchURL->query($sql);
        $order = $sth->fetchObject();
        $start_date = '2019/01/01';
        if ($order) {
            $time = strtotime($order->order_time);
            $start_date = date('Y/m/d', $time);
        }
        $end_date = date('Y/m/d');
        $start = $start ? : $start_date;
        $end = $end ? : $end_date;

        return array(
            'payments' => $payments,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'start' => $start,
            'end' => $end,
            'static_version' => $this->static_version,
            'total' => $total,
            'overflow' => $overflow,
        );
    }

    public function consume()
    {
        $uid = $_SESSION['uid'];
        $start = isset($_GET['start']) ? $_GET['start'] : null;
        $end = isset($_GET['end']) ? $_GET['end'] : null;
        $start_date = '2019/01/01';
        $end_date = date('Y/m/d');
        $SearchURL = new \Model\SearchURL();
        $limit = 20;
        $total = $overflow = 0;
        $consumes = $variable = array();

        // 筛选日期
        $filter = '';
        if (preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $start, $matches)) {
            $start_time = str_replace('/', '-', $start);
            $filter .= " AND order_time >= '$start_time 00:00:00'";
        }
        if (preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $end, $matches)) {
            $end_time = str_replace('/', '-', $end);
            $filter .= " AND order_time <= '$end_time 23:59:59'";
        }

        // 总条数
        $sql = "SELECT COUNT(1) AS total 
FROM $this->db.pl_order_flow_t A 
LEFT JOIN $this->db.pl_payment_flow_t B ON B.order_flow_no = A.order_flow_no 
WHERE user_id = '$uid' AND order_type_code = 'CONSUME' AND refund_flag = 0 $filter ";

        $sth = $SearchURL->query($sql);
        $var = $sth->fetchObject();
        if ($var) {
            $total = $var->total;
        }

        if ($total) {
            // 所有条目
            $sql = "SELECT order_time, device_name, order_amount, payment_amount, balance, acct_type_no, request_flow_no, param_name, payment_flow_no 
FROM $this->db.pl_order_flow_t A 
LEFT JOIN $this->db.pl_payment_flow_t B ON B.order_flow_no = A.order_flow_no 
LEFT JOIN $this->db.pl_logical_device_t C ON C.operator_id = A.operator_id AND C.device_no = A.device_no 
LEFT JOIN $this->db.xf_assistant_flow_t D ON D.order_flow_no = A.order_flow_no 
LEFT JOIN $this->db.pl_parameter_code_t E ON E.param_type='consume_model' AND E.param_code=D.consume_model
WHERE user_id = '$uid' AND order_type_code = 'CONSUME' AND refund_flag = 0 $filter 
ORDER BY B.payment_flow_no DESC 
LIMIT $limit";

            $statement = $SearchURL->query($sql);
            $variable = $statement->fetchAll(\PDO::FETCH_OBJ);

            // 最早日期
            $sql = "SELECT order_time FROM $this->db.pl_order_flow_t WHERE user_id = '$uid' AND order_type_code = 'CONSUME' ORDER BY order_time LIMIT 1";
            $sth = $SearchURL->query($sql);
            $order = $sth->fetchObject();
            if ($order) {
                $time = strtotime($order->order_time);
                $start_date = date('Y/m/d', $time);
            }
        }

        $start = $start ? : $start_date;
        $end = $end ? : $end_date;
        $overflow = $limit >= $total ? 1 : 0;

        // 合并条目
        $previous = null;
        $i = 0;
        foreach ($variable as $value) {
            if ($previous) {
                if ($value->request_flow_no == $previous->request_flow_no) {
                    $arr = array();
                    $arr[$previous->acct_type_no] = $previous;
                    $arr[$value->acct_type_no] = $value;
                    $consumes[$value->request_flow_no] = $arr;
                } else {
                    $consumes[$value->request_flow_no] = $value;
                }
            } else {
                $consumes[$value->request_flow_no] = $value;
            }
            $previous = $value;
            $i++;
        }

        return array(
            'consumes' => $consumes,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'start' => $start,
            'end' => $end,
            'static_version' => $this->static_version,
            'total' => $total,
            'overflow' => $overflow,
        );
    }

    /* 挂失解挂 */
    public function loss()
    {
        $uid = $_SESSION['uid'];
        $oid = $_SESSION['oid'];
        $card = $_SESSION['card'];
        $SearchURL = new \Model\SearchURL();

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $card_status = $_POST['card_status'];
            $status = $type = null;
            switch ($card_status) {
                case 'RELEASED':
                    $status = 'LOST';
                    $type = 'LOSS_REPORT';
                    break;
                case 'LOST':
                    $status = 'RELEASED';
                    $type = 'HANG_CARD';
                    break;
            }

            // 卡信息更新状态
            if ($status) {
                $sql = "UPDATE $this->db.pl_card_t 
SET card_status = '$status' 
WHERE `user_id` = '$uid' AND `operator_id` = '$oid' AND `card_code` = '$card->card_code' 
LIMIT 1";
                $count = $SearchURL->exec($sql);

                // 插入卡操作记录
                if ($count) {
                    $flow_no = date('YmdHis') . substr(explode(' ', microtime())[0], 2, 7);
                    $time = date('Y-m-d H:i:s');
                    $sql = "INSERT INTO $this->db.pl_card_history_t 
SET operate_flow_no = '$flow_no', user_id = '$uid', card_id = '$card->card_id', operate_type = '$type', operate_time = '$time', operator_id = '$oid'";
                    $count = $SearchURL->exec($sql);
                }
            }

            header('Location: /card/loss');
            exit;
        }

        // 读取卡信息
        $sql = "SELECT param_name, card_code, card_status 
FROM $this->db.pl_card_t A 
LEFT JOIN $this->db.pl_parameter_code_t B ON B.param_type='card_status' AND B.param_code = A.card_status 
WHERE user_id = '$uid' 
LIMIT 1";

        $statement = $SearchURL->query($sql);
        $card = $statement->fetchObject();

        return array(
            'card' => $card,
            'static_version' => $this->static_version,
        );
    }

    public function info()
    {
        $uid = $_SESSION['uid'];
        $tip = isset($_GET['tip']);
        $year = date('Y');
        $err = null;
        $SearchURL = new \Model\SearchURL();

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            extract($_POST);
            $user_name = trim($user_name);
            $time = strtotime($birthday);

            // 异常检测
            if (!$user_name) {
                $err = '请输入姓名';
            }

            if (false === $time) {
                $err = '请正确输入生日';
            } else {
                $date = date('Y-m-d H:i:s', $time);
            }

            // 更新数据
            if (!$err) {
                $sql = "UPDATE $this->db.pl_user_t 
SET user_name = '$user_name', sex = '$sex', birthday = '$date' 
WHERE user_id = '$uid' 
LIMIT 1";
                $count = $SearchURL->exec($sql);

                header("Location: /card/info?tip");
                exit;
            }

            $user = (object) $_POST;

        } else {
            $sql = "SELECT user_name, sex, birthday, user_id 
FROM $this->db.pl_user_t 
WHERE user_id = '$uid' 
LIMIT 1";

            $statement = $SearchURL->query($sql);
            $user = $statement->fetchObject();
            $birthday = $user->birthday;
            $time = strtotime($birthday);
            $user->birthday = $date = date('Y/m/d', $time);
        } 

        return array(
            'user' => $user,
            'year' => $year,
            'err' => $err,
            'static_version' => $this->static_version,
            'tip' => $tip,
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
