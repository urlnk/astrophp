<?php

namespace App;

class Func
{
    public static $key = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';

    /* 转换卡号 */
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

    /* 加密 */
    public static function encrypt($data)
    {
        $str = base64_encode($data);
        $len = strlen($str);
        $arr = [];
        for ($i = 0; $i < $len; $i++) {
            $idx = self::idx($str[$i]);
            $arr[] = self::chr($idx, 1);
        }
        $arr = implode('', $arr);
        return $arr;
    }

    /* 解密 */
    public static function decrypt($str)
    {
        $len = strlen($str);
        $arr = [];
        for ($i = 0; $i < $len; $i++) {
            $idx = self::idx($str[$i]);
            $arr[] = self::chr($idx, -1);
        }
        $arr = implode('', $arr);
        $arr = base64_decode($arr);
        return $arr;
    }

    /* 获取字符索引 */
    public static function idx($key)
    {
        $arr = self::$key;
        $len = strlen($arr);
        for ($i = 0; $i < $len; $i++) {
            if ($key === $arr[$i]) {
                return $i;
            }
        }
        return false;
    }

    /* 获取字符 */
    public static function chr($idx, $offset = 0)
    {
        $arr = self::$key;
        $len = strlen($arr);
        for ($i = 0; $i < $len; $i++) {
            if ($idx === $i) {
                $j = $idx + $offset;
                if (64 < $j) {
                    $j = $j - 65;
                } elseif (0 > $j) {
                    $j = 65 + $j;
                }
                return $arr[$j];
            }
        }
        return false;
    }
}
