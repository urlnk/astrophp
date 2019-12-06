<?php

namespace App\Card\Controller;

class Api extends _controller
{
    public function log()
    {
        $uid = $_SESSION['uid'];
        $start = isset($_GET['start']) ? $_GET['start'] : null;
        $end = isset($_GET['end']) ? $_GET['end'] : null;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $SearchURL = new \Model\SearchURL();
        $limit = 20;
        $offset = $limit * $page - $limit;
        $code = $total = 0;
        $msg = '';
        $data = array();

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
        $pages = ceil($total / $limit);
        $code = $overflow = (int) ($page >= $pages);
        if ($overflow) {
            $msg = '没有更多数据了';
        }

        // 所有条目
        $sql = "SELECT order_amount, param_name, order_time 
FROM $this->db.pl_order_flow_t A 
LEFT JOIN $this->db.pl_parameter_code_t B ON B.param_type='payment_code' AND B.param_code=A.payment_code 
WHERE user_id = '$uid' AND order_type_code = 'RECHARGE' AND refund_flag = '0' $filter 
ORDER BY order_flow_no DESC 
LIMIT $offset, $limit";

        $statement = $SearchURL->query($sql);
        $payments = $statement->fetchAll(\PDO::FETCH_OBJ);

        // 遍历数据
        foreach ($payments as $payment) {
            $data[] = array(
                'order_amount' => $payment->order_amount,
                'param_name' => $payment->param_name,
                'order_time' => $payment->order_time,
            );
        }

        $arr = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        );
        echo json_encode($arr);
        exit;
    }

    public function consume()
    {
        $uid = $_SESSION['uid'];
        $start = isset($_GET['start']) ? $_GET['start'] : null;
        $end = isset($_GET['end']) ? $_GET['end'] : null;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $SearchURL = new \Model\SearchURL();
        $limit = 20;
        $offset = $limit * $page - $limit;
        $code = $total = 0;
        $msg = '';
        $consumes = $data = array();

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
WHERE user_id = '$uid' AND order_type_code = 'CONSUME' AND refund_flag = 0 $filter ";

        $sth = $SearchURL->query($sql);
        $var = $sth->fetchObject();
        if ($var) {
            $total = $var->total;
        }
        $pages = ceil($total / $limit);
        $code = $overflow = (int) ($page >= $pages);
        if ($overflow) {
            $msg = '没有更多数据了';
        }

        // 所有条目
        $sql = "SELECT order_time, device_name, order_amount, payment_amount, balance, acct_type_no, request_flow_no, param_name, payment_flow_no 
FROM $this->db.pl_order_flow_t A 
LEFT JOIN $this->db.pl_payment_flow_t B ON B.order_flow_no = A.order_flow_no 
LEFT JOIN $this->db.pl_logical_device_t C ON C.operator_id = A.operator_id AND C.device_no = A.device_no 
LEFT JOIN $this->db.xf_assistant_flow_t D ON D.order_flow_no = A.order_flow_no 
LEFT JOIN $this->db.pl_parameter_code_t E ON E.param_type='consume_model' AND E.param_code=D.consume_model
WHERE user_id = '$uid' AND order_type_code = 'CONSUME' AND refund_flag = 0 $filter 
ORDER BY B.payment_flow_no DESC 
LIMIT $offset, $limit";

        $statement = $SearchURL->query($sql);
        $variable = $statement->fetchAll(\PDO::FETCH_OBJ);

        // 合并条目
        $previous = null;
        $i = 0;
        foreach ($variable as $value) {
            $value->request_flow_no = strtotime($value->order_time);
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

        // 遍历数据
        foreach ($consumes as $consume) {
            $attr = '';
            if (is_array($consume)) {
                foreach ($consume as $value) {
                    if (3 == $value->acct_type_no) {
                        $attr .= 'data-row';
                    } else {
                        $attr .= 'data-cash';
                    }
                    $attr .= "='{\"balance\":\"$value->balance\", \"order_amount\":\"$value->payment_amount\"}' ";
                }
                $consume = $value;
            } else {
                if (3 == $consume->acct_type_no) {
                    $attr .= 'data-row';
                } else {
                    $attr .= 'data-cash';
                }
                $attr .= "='{\"balance\":\"$consume->balance\", \"order_amount\":\"$consume->payment_amount\"}' ";
            }

            $data[] = array(
                'attr' => $attr,
                'order_amount' => $consume->order_amount,
                'param_name' => $consume->param_name,
                'order_time' => $consume->order_time,
                'device_name' => $consume->device_name,
            );
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
