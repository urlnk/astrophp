<?php

namespace App\Ka\Controller;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

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
            # $decimal = '507015516';

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

    public function sms()
    {
        $phone = isset($_GET['phone']) ? trim($_GET['phone']) : null;
        $oid = isset($_GET['oid']) ? trim($_GET['oid']) : null;
        $code = 0;
        $msg = '';
        $data = array();
        $SearchURL = new \Model\SearchURL();

        // 检测输入
        if (!preg_match('/^1\d{10}$/', $phone)) {
            $msg = '请输入正确的手机号码';

        } elseif (!($oid > 99999 && $oid < 100100)) {
            $msg = '商户号不正确';
        }

        if (!$msg) {
            // 查找用户
            $sql = "SELECT user_id, user_name FROM $this->db.pl_user_t WHERE telephone = '$phone' AND operator_id = '$oid' LIMIT 1";
            $statement = $SearchURL->query($sql);
            $user = $statement->fetchObject();
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
            $result = $this->sendSms($phone, $rand);
            if (isset($result['result']['Code']) && 'OK' == $result['result']['Code']) {
                $msg = '发送成功，请注意查收！';
                $this->smsLog($phone, $rand, $oid);

            } else {
                $msg = '短信服务异常，请稍后重试！';
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

    public function smsLog($phone, $code, $oid)
    {
        $SearchURL = new \Model\SearchURL();
        $now = date('Y-m-d H:i:s');
        $sql = "INSERT INTO $this->db.pl_sms_code_t SET mobile_no = '$phone', send_content = '$code', operator_id = '$oid', send_time = '$now'";
        return $count = $SearchURL->exec($sql);
    }

    public function sendSms($phone, $code)
    {
        global $_CONFIG;
        AlibabaCloud::accessKeyClient($_CONFIG['smsApi']['accessKeyId'], $_CONFIG['smsApi']['accessSecret'])
                        ->regionId('cn-hangzhou')
                        ->asDefaultClient();

        $res = array('result' => array(), 'errNo' => 0, 'errMsg' => '');

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
                                                  'SignName' => "易捷一卡通",
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
}
