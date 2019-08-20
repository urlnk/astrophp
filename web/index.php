<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use EquivRoute\Router;
use NewUI\Engine;

global $_CONFIG, $template;
define('ROOT', dirname(__DIR__));

require ROOT . '/vendor/autoload.php';
$_CONFIG = include ROOT . '/app/config.php';
$config = include ROOT . '/app/route.php';

$router = new Router($config['name'], $config['routes'], $config['options']);
$template = new Engine(ROOT . '/app/template');
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_CONFIG['uri_custom'] ? : $_SERVER['REQUEST_URI'];

$routeInfo = $router->dispatch($httpMethod, $uri);

include ROOT . '/example/' . $_CONFIG['example'] . '.php';
