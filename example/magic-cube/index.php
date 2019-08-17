<?php

namespace Virgo\Example;

use MagicCube\Dispatcher;

class Index
{
    public $dispatcher = null;

    public function __construct($routeInfo)
    {
        $this->init($routeInfo);
    }

    public function init($routeInfo)
    {
        $config = include ROOT . '/app/module.php';
        $this->dispatcher = $dispatcher = new Dispatcher($routeInfo);
        $dispatcher->setVars($config);
    }

    public function dispatch()
    {
        $this->dispatcher->dispatch();
    }
}

$index = new Index($routeInfo);
$index->dispatch();
