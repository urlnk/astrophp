<?php

namespace App\Ka\Controller;

class Index extends _controller
{
    public function index()
    {
        return array(
            'static_version' => $this->static_version,
        );
    }
}
