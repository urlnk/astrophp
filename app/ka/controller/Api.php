<?php

namespace App\Ka\Controller;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use App\Func;

class Api extends _controller
{
    /* 刷卡 */
    public function swipe()
    {
        $no = isset($_POST['no']) ? trim($_POST['no']) : null;
        $test = isset($_POST['test']) ? trim($_POST['test']) : null;
        $code = 0;
        $msg = '';
        $data = array();
        $SearchURL = new \Model\SearchURL();

        if (is_numeric($no) || $test) {
            $decimal = $test ? : Func::hexConvert($no);
            # $decimal = '507015516';

            // 查找用户
            $sql = "SELECT A.user_id AS user_id, user_name, telephone, operator_name, organ_name, A.operator_id, card_status 
FROM $this->db.pl_card_t A 
LEFT JOIN $this->db.pl_user_t B ON B.user_id = A.user_id 
LEFT JOIN $this->db.pl_operator_info_t C ON C.operator_id = A.operator_id 
LEFT JOIN $this->db.pl_organization_t D ON D.organ_id = B.organ_id 
WHERE A.card_code = '$decimal' AND A.card_status != 'UNRELEASE' 
LIMIT 50";
            $statement = $SearchURL->query($sql);
            $data = $statement->fetchAll(\PDO::FETCH_OBJ);
            $len = count($data);
            if (!$len) {
                $code = 2;
                $msg = '没有找到用户，请重刷！';
            }

        } else {
            $code = 2;
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

    /* 发短信 */
    public function sms()
    {
        $phone = isset($_GET['phone']) ? trim($_GET['phone']) : null;
        $oid = isset($_GET['oid']) ? trim($_GET['oid']) : null;
        $find= isset($_GET['find']) ? trim($_GET['find']) : null;
        $captcha= isset($_GET['captcha']) ? trim($_GET['captcha']) : null;
        $code = 0;
        $msg = '';
        $data = array();
        $SearchURL = new \Model\SearchURL();

        // 检测输入
        if (!preg_match('/^1\d{10}$/', $phone)) {
            $msg = '请输入正确的手机号码';

        } elseif ($oid && !($oid > 99999 && $oid < 100100)) {
            $msg = '商户号不正确';
        }

        if (!$msg) {
            // 查找用户
            $user = true;
            if (!$find) {
                $sql = "SELECT user_id, user_name FROM $this->db.pl_user_t WHERE telephone = '$phone' AND operator_id = '$oid' LIMIT 1";
                $statement = $SearchURL->query($sql);
                $user = $statement->fetchObject();
            }

            if (!$user) {
                $msg = '在该商户没有找到这个手机号';

            } else {
                // 时间限制
                $sql = "SELECT send_time FROM $this->db.pl_sms_code_t WHERE mobile_no = '$phone' AND operator_id = '$oid' ORDER BY `sms_id` DESC LIMIT 1";
                $sth = $SearchURL->query($sql);
                $sms = $sth->fetchObject();
                if ($sms) {
                    $oldTime = strtotime($sms->send_time);
                    $nowTime = time();
                    # $nowTime = 1575285282;
                    $stepTime = $nowTime - $oldTime;
                    if ($stepTime < 60) {
                        $msg = '距上次发送短信间隔时间小于一分钟，请稍后重试！';
                    }
                }
                # print_r(get_defined_vars());
            }
        }

        // 生成验证码
        $rand = mt_rand(0, 9999);
        $len = strlen($rand);
        while ($len < 4) {
            $rand = '0' . $rand;
            $len++;
        }

        $code = 3;
        if (!$msg) {
            $result = $this->sendSms($phone, $rand, $captcha);
            if (isset($result['result']['Code']) && 'OK' == $result['result']['Code']) {
                $msg = '发送成功，请注意查收！';
                $this->smsLog($phone, $rand, $oid);

            } else {
                $msg = '短信服务异常，请稍后重试！';
                $data = $result;
            }
        }

        $arr = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        );
        echo json_encode($arr);
        exit;
    }

    /* 短信记录 */
    public function smsLog($phone, $code, $oid)
    {
        $SearchURL = new \Model\SearchURL();
        $now = date('Y-m-d H:i:s');
        $sql = "INSERT INTO $this->db.pl_sms_code_t SET mobile_no = '$phone', send_content = '$code', operator_id = '$oid', send_time = '$now'";
        return $count = $SearchURL->exec($sql);
    }

    /* 短信接口 */
    public function sendSms($phone, $code, $captcha)
    {
        global $_CONFIG;
        AlibabaCloud::accessKeyClient($_CONFIG['smsApi']['accessKeyId'], $_CONFIG['smsApi']['accessSecret'])
                        ->regionId('cn-hangzhou')
                        ->asDefaultClient();

        $res = array('result' => array(), 'errNo' => 0, 'errMsg' => '');

        if ($captcha) {
            $res['result']['Code'] = 'OK';
            return $res;
        }

        try {
            $result = AlibabaCloud::rpc()
                                  ->product('Dysmsapi')
                                  // ->scheme('https') // https | http
                                  ->version('2017-05-25')
                                  ->action('SendSms')
                                  ->method('POST')
                                  ->host('dysmsapi.aliyuncs.com')
                                  ->options([
                                                'query' => [
                                                  'RegionId' => "cn-hangzhou",
                                                  'PhoneNumbers' => $phone,
                                                  'SignName' => "易卡通",
                                                  'TemplateCode' => "SMS_179075235",
                                                  'TemplateParam' => "{\"code\":\"$code\"}",
                                                ],
                                            ])
                                  ->request();

            $res['result'] = $result->toArray();

        } catch (ClientException $e) {
            $res['errMsg'] = $e->getErrorMessage();
            $res['errNo'] = 1;

        } catch (ServerException $e) {
            $res['errMsg'] = $e->getErrorMessage();
            $res['errNo'] = 2;
        }

        return $res;
    }

    /* 账户信息 */
    public function account()
    {
        $uid = isset($_POST['uid']) ? trim($_POST['uid']) : null;
        $code = 0;
        $msg = '';
        $data = array();
        $SearchURL = new \Model\SearchURL();

        if (is_numeric($uid)) {
            $arr = array(
                0,
                0,
                0,
                0,
            );

            $sql = "SELECT acct_type, balance FROM $this->db.pl_acct_balance_t WHERE user_id = '$uid' LIMIT 2";
            $SearchURL = new \Model\SearchURL();
            $sth = $SearchURL->query($sql);
            $balances = $sth->fetchAll(\PDO::FETCH_OBJ);
            foreach ($balances as $balance) {
                $arr[$balance->acct_type] = $balance->balance;
            }

            $sql = "SELECT card_code, effective_time FROM $this->db.pl_card_t WHERE user_id = '$uid' LIMIT 1";
            $sth = $SearchURL->query($sql);
            $card = $sth->fetchObject();

            $sql = "SELECT telephone FROM $this->db.pl_user_t WHERE user_id = '$uid' LIMIT 1";
            $sth = $SearchURL->query($sql);
            $user = $sth->fetchObject();

            $data['cash'] = $arr[1];
            $data['subsidy'] = $arr[3];
            $data['card_code'] = $card->card_code;
            $data['effective_time'] = $card->effective_time;
            $data['telephone'] = $user->telephone;

        } else {
            $code = 3;
            $msg = '不是有效的用户ID';
        }

        $arr = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        );
        echo json_encode($arr);
        exit;
    }

    /* 充值/消费订单日期 */
    public function order()
    {
        $uid = isset($_GET['uid']) ? trim($_GET['uid']) : null;
        $type = isset($_GET['type']) ? trim($_GET['type']) : null;
        $SearchURL = new \Model\SearchURL();
        $code = 0;
        $msg = '';
        $orderType = $type ? 'CONSUME' : 'RECHARGE';

        // 最早日期
        $sql = "SELECT order_time FROM $this->db.pl_order_flow_t WHERE user_id = '$uid' AND order_type_code = '$orderType' ORDER BY order_time LIMIT 1";
        $sth = $SearchURL->query($sql);
        $order = $sth->fetchObject();
        $start_date = '2019/01/01';
        if ($order) {
            $time = strtotime($order->order_time);
            $start_date = date('Y/m/d', $time);
        }
        $end_date = date('Y/m/d');

        $data = array(
            'start_date' => $start_date,
            'end_date' => $end_date,
        );

        $arr = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        );
        echo json_encode($arr);
        exit;
    }

    /* 充值记录 */
    public function log()
    {
        $uid = isset($_GET['uid']) ? trim($_GET['uid']) : null;
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

    /* 消费记录 */
    public function consume()
    {
        $uid = isset($_GET['uid']) ? trim($_GET['uid']) : null;
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

    /* 挂失解挂 */
    public function loss()
    {
        $uid = isset($_GET['uid']) ? trim($_GET['uid']) : null;
        $code = 0;
        $msg = '';
        $data = array();
        $SearchURL = new \Model\SearchURL();

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $uid = $_POST['uid'];
            $card_status = $_POST['card_status'];
            $status = $type = $text = $param_name = null;
            switch ($card_status) {
                case 'RELEASED':
                    $status = 'LOST';
                    $type = 'LOSS_REPORT';
                    $text = '挂失成功';
                    $param_name = '挂失';
                    break;
                case 'LOST':
                    $status = 'RELEASED';
                    $type = 'HANG_CARD';
                    $text = '解挂成功';
                    $param_name = '已发行';
                    break;
            }

            // 读取卡信息
            $sql = "SELECT param_name, card_code, card_status, card_id, operator_id 
FROM $this->db.pl_card_t A 
LEFT JOIN $this->db.pl_parameter_code_t B ON B.param_type='card_status' AND B.param_code = A.card_status 
WHERE user_id = '$uid' 
LIMIT 1";

            $sth= $SearchURL->query($sql);
            $card = $sth->fetchObject();

            if ($card) {
                $data = $card;
                $data->card_status = $status;
                $data->param_name = $param_name;

            } else {
                $code = 1;
                $msg = '无卡用户';
            }

            // 卡信息更新状态
            if ($card && $status) {
                $sql = "UPDATE $this->db.pl_card_t 
SET card_status = '$status' 
WHERE `user_id` = '$uid' 
LIMIT 1";
                $count = $SearchURL->exec($sql);

                // 插入卡操作记录
                if ($count) {
                    $flow_no = date('YmdHis') . substr(explode(' ', microtime())[0], 2, 7);
                    $time = date('Y-m-d H:i:s');
                    $sql = "INSERT INTO $this->db.pl_card_history_t 
SET operate_flow_no = '$flow_no', user_id = '$uid', card_id = '$card->card_id', operate_type = '$type', operate_time = '$time', operator_id = '$card->operator_id'";
                    $count = $SearchURL->exec($sql);
                    if ($count) {
                        $code = 1;
                        $msg = $text;
                    }
                }
            }

        } else {
            // 读取卡信息
            $sql = "SELECT param_name, card_code, card_status 
FROM $this->db.pl_card_t A 
LEFT JOIN $this->db.pl_parameter_code_t B ON B.param_type='card_status' AND B.param_code = A.card_status 
WHERE user_id = '$uid' 
LIMIT 1";

            $sth= $SearchURL->query($sql);
            $card = $sth->fetchObject();
            if ($card) {
                $data = $card;

            } else {
                $code = 1;
                $msg = '无卡用户';
            }
        }

        $arr = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        );
        echo json_encode($arr);
        exit;
    }

    /* 完善信息 */
    public function info()
    {
        $uid = isset($_GET['uid']) ? trim($_GET['uid']) : null;
        $code = 0;
        $msg = $err = '';
        $data = array();
        $SearchURL = new \Model\SearchURL();

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            extract($_POST);
            $user_name = trim($user_name);
            $time = strtotime($birthday);

            // 异常检测
            if (!$user_name) {
                $err = '请输入姓名';
            } elseif (!$sex) {
                $err = '请选择性别';
            } elseif (false === $time) {
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
                if ($count) {
                    $msg = '修改成功';
                }

            } else {
                $code = 1;
                $msg = $err;
            }

            $data = (object) $_POST;
            # print_r($_POST);exit;

        } else {
            $data = $SearchURL->getInfo($uid);
        }
        $data->year = date('Y');
        $data->today = date('Y/m/d');

        $arr = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        );
        echo json_encode($arr);
        exit;
    }

    /* 绑定手机号 */
    public function phone()
    {
        $uid = isset($_POST['uid']) ? trim($_POST['uid']) : null;
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : null;
        $verify = isset($_POST['code']) ? trim($_POST['code']) : null;
        $captcha = isset($_POST['captcha']) ? trim($_POST['captcha']) : null;
        $code = 0;
        $msg = '绑定成功';
        $data = array();
        $SearchURL = new \Model\SearchURL();

        if (!preg_match('/^1\d{10}$/', $phone)) {
            $msg = '请输入正确的手机号码';
            $code = 1;
        } else {
            $err = null;
            if (!$captcha) {
                $err = $this->captcha($phone, $verify);
            }

            if ($err) {
                $code = 2;
                $msg = $err;
            } else {
                $sql = "UPDATE $this->db.pl_user_t 
SET telephone = '$phone' 
WHERE user_id = '$uid' 
LIMIT 1";
                $count = $SearchURL->exec($sql);
            }
        }

        $arr = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        );
        echo json_encode($arr);
        exit;
    }

    /* 手机号登录 */
    public function login()
    {
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : null;
        $verify = isset($_POST['code']) ? trim($_POST['code']) : null;
        $captcha = isset($_POST['captcha']) ? trim($_POST['captcha']) : null;
        $code = 0;
        $msg = '';
        $data = array();
        $SearchURL = new \Model\SearchURL();

        if (!preg_match('/^1\d{10}$/', $phone)) {
            $msg = '请输入正确的手机号码';
            $code = 1;
        } else {
            $err = null;
            if (!$captcha) {
                $err = $this->captcha($phone, $verify);
            }

            if ($err) {
                $code = 2;
                $msg = $err;
            } else {
                // 查找用户
                $sql = "SELECT A.user_id AS user_id, user_name, telephone, operator_name, organ_name, A.operator_id, card_status 
FROM $this->db.pl_card_t A 
LEFT JOIN $this->db.pl_user_t B ON B.user_id = A.user_id 
LEFT JOIN $this->db.pl_operator_info_t C ON C.operator_id = A.operator_id 
LEFT JOIN $this->db.pl_organization_t D ON D.organ_id = B.organ_id 
WHERE B.telephone = '$phone' AND A.card_status != 'UNRELEASE' 
LIMIT 50";
                $statement = $SearchURL->query($sql);
                $data = $statement->fetchAll(\PDO::FETCH_OBJ);
                $len = count($data);
                if (!$len) {
                    $code = 3;
                    $msg = '没有找到绑定这个手机号的用户';
                }
            }
        }

        $arr = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        );
        echo json_encode($arr);
        exit;
    }

    public function captcha($phone, $code)
    {
        $msg = null;
        $SearchURL = new \Model\SearchURL();
        $sql = "SELECT send_time FROM $this->db.pl_sms_code_t WHERE mobile_no = '$phone' AND send_content = '$code' ORDER BY `sms_id` DESC LIMIT 1";
        $sth = $SearchURL->query($sql);
        $sms = $sth->fetchObject();
        if ($sms) {
            $oldTime = strtotime($sms->send_time);
            $nowTime = time();
            # $nowTime = 1575285282;
            $stepTime = $nowTime - $oldTime;
            if ($stepTime > 600) {
                $msg = '验证码已经过期，请重新发送验证码！';
            }
        } else {
            $msg = '验证码不正确';
        }
        return $msg;
    }
}
