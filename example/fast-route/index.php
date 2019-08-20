<?php

namespace Virgo\Example;

use FastRoute\Dispatcher;

class Index
{
    public function __construct($routeInfo)
    {
        $this->init($routeInfo);
    }

    public function init($routeInfo)
    {
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                // ... call $handler with $vars
                break;
        }
    }
}

print_r($routeInfo);
$index = new Index($routeInfo);
