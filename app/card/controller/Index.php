<?php

namespace App\Card\Controller;

class Index extends _controller
{
    public function index()
    {
        $card = $_SESSION['card'];

        return array(
            'card' => $card,
        );
    }
}
