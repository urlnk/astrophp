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
        $dispatcher->_setVars($config);
    }

    public function dispatch($return = null)
    {
        return $result = $this->dispatcher->dispatch($return);
    }
}

$index = new Index($routeInfo);
$result = $index->dispatch($_CONFIG['debug']);
if ($_CONFIG['debug']) {
    // 不要使用注释手动切换
    print_r(array($result, __FILE__, __LINE__));
}
