<?php

namespace App\Index\Controller;

class Index extends \MagicCube\Controller
{
    public function index()
    {
        $arr = array();
        $arr[] = array(
            array(100, '百度'),
            array(191571521, '搜狗'),
            array(360, '360搜索'),
        );
        $arr[] = array(
            array(900916, 'Google'),
            array(251, 'Yahoo'),
            array(0, 'DuckDuckGo'),
        );
        $arr[] = array(
            array(2, '海词'),
            array(3, '优惠券'),
        );
        return ['arr' => $arr];
    }
}
