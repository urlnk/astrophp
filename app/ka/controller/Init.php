<?php

namespace App\Ka\Controller;

class Init extends _controller
{
    public function index()
    {
        $_GET['_'] = time();
        $queryString = http_build_query($_GET);
        header("Location: /ka?$queryString");
        exit;
    }
}
