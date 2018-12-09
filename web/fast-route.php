<?php

use FastRoute\RouteParser\Std as RouteParser;
use FastRoute\DataGenerator\GroupCountBased as DataGenerator;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;

$routeCollector = new FastRoute\RouteCollector(new RouteParser, new DataGenerator);

$routeCollector->addRoute(['GET', 'POST'], '/users', 'get_all_users_handler');
$prefix = '/admin';
$prefix = '';
$routeCollector->post($prefix . '/user[/{id:\d+}[/{name}]]', 'get_user_handler');

$routeCollector->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
$routeCollector->addRoute('GET', '/user/{name}', 'user_name_handler');
# $routeCollector->addRoute('GET', '/user/{name:.+}', 'user_name_plus_handler');

$dispatcher = new Dispatcher($routeCollector->getData());

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
# $routeInfo = $router->dispatch($httpMethod, $uri);
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
