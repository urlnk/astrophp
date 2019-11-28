<?php

namespace App\Card\Controller;

class Index extends _controller
{
    public function index()
    {
        $user = $_SESSION['user'];

        return array(
            'user' => $user,
            'static_version' => $this->static_version,
        );
    }
}
