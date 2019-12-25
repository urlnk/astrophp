<?php

namespace App\Ka\Controller;

class Index extends _controller
{
    public function index()
    {
        global $_CONFIG;
        $captcha = isset($_GET['captcha']) ? 1 : 0;
        $hta = isset($_GET['hta']) ? 1 : 0;
        $test = isset($_GET['test']) ? $_GET['test'] : null;
        $no = isset($_COOKIE['no']) ? $_COOKIE['no'] : null;
        $interval = $_CONFIG['js']['interval'];
        if ($hta) {
            $h = $_GET['hta'];
            if (is_numeric($h)) {
                $interval = $h;
            }
        }

        // 区别地址
        $ads = $_CONFIG['ads'];
        /*
        if ($no) {
            $ads = [];
            $ad = $_CONFIG['ads'];
            foreach ($ad as $row) {
                $ads[] = $row . '?no=' .$no;
            }
        }
        */

        return array(
            'static_version' => $this->static_version,
            'interval' => $interval,
            'hta' => $hta,
            'testCardCode' => $test,
            'captcha' => $captcha,
            'ads' => json_encode($ads),
            'bg' => json_encode($_CONFIG['bg']),
            'bgChange' => $_CONFIG['bgChange'],
            'adChange' => $_CONFIG['adChange'],
            'adHide' => $_CONFIG['adHide'],
            'adFullscreen' => $_CONFIG['adFullscreen'],
            'countdown' => $_CONFIG['countdown'],
            'exitTime' => $_CONFIG['exitTime'],
            'backTime' => $_CONFIG['backTime'],
            'refresh' => $_CONFIG['refresh'],
            'videoType' => $_CONFIG['video_type'],
            'logo' => $_CONFIG['logo'],
            'qrcode' => $_CONFIG['qrcode'],
        );
    }
}
