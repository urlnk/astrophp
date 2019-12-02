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
}
