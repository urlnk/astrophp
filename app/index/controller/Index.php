<?php

namespace App\Index\Controller;

class Index extends \MagicCube\Controller
{
    public function index()
    {
        # return __FILE__;
        return ['hello' => 'world', 'nihao'];
    }
}
