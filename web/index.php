<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use EquivRoute\Router;

define('ROOT', dirname(__DIR__));

require ROOT . '/vendor/autoload.php';
$config = include ROOT . '/app/route.php';

$router = new Router($config['name'], $config['routes'], $config['options']);

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);
# $uri = 'get,post:users!wubenli@documents$1%2Fview';
# $uri = '$1';

$routeInfo = $router->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // ... call $handler with $vars
        break;
}
print_r($routeInfo);

include ROOT . '/example/magic-cube/index.php';
