<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('PRC');

use EquivRoute\Router;
use NewUI\Engine;
use Topdb\Table;

global $_CONFIG, $template;
define('ROOT', dirname(__DIR__));
defined('ROOT_DIR') or define('ROOT_DIR', 'e:/env');

require ROOT . '/vendor/autoload.php';
$_CONFIG = include ROOT . '/app/config.php';
$route = include ROOT . '/app/route.php';

$router = new Router($route['name'], $route['routes'], $route['options']);
$template = new Engine(ROOT . '/app/template');
Table::init($_CONFIG['database'], 'catfan/medoo');

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_CONFIG['uri_custom'] ? : $_SERVER['REQUEST_URI'];
$routeInfo = $router->dispatch($httpMethod, $uri);

include ROOT . '/example/' . $_CONFIG['example'] . '.php';
