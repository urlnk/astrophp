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
        $interval = $_CONFIG['js']['interval'];
        if ($hta) {
            $h = $_GET['hta'];
            if (is_numeric($h)) {
                $interval = $h;
            }
        }

        return array(
            'static_version' => $this->static_version,
            'interval' => $interval,
            'hta' => $hta,
            'testCardCode' => $test,
            'captcha' => $captcha,
            'ads' => json_encode($_CONFIG['ads']),
            'bg' => json_encode($_CONFIG['bg']),
            'bgChange' => $_CONFIG['bgChange'],
            'adChange' => $_CONFIG['adChange'],
            'adHide' => $_CONFIG['adHide'],
        );
    }
}
