<?php

namespace App\Card\Controller;

class Index extends _controller
{
    public function index()
    {
        $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        return array(
            'user' => $user,
            'static_version' => $this->static_version,
        );
    }
}
