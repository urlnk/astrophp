<?php

namespace App\Ka\Controller;

class Index extends _controller
{
    public function index()
    {
        global $_CONFIG;
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
        );
    }
}
