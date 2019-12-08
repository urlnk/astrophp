<?php

namespace App;

class Func
{
    // 转换卡号
    public static function hexConvert($no)
    {
        $hex = base_convert($no, 10, 16);
        $len = strlen($hex);
        while ($len < 8) {
            $hex = '0' . $hex;
            $len++;
        }
        $front = substr($hex, 0, 4);
        $back = substr($hex, 4, 4);
        $str = $back . $front;
        return $decimal = base_convert($str, 16, 10);
    }
}
