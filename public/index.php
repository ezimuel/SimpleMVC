<?php
declare(strict_types=1);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use DI\ContainerBuilder;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Laminas\Diactoros\ServerRequestFactory;
use SimpleMVC\Controller\Error404;

$builder = new ContainerBuilder();
$builder->addDefinitions('config/container.php');
$container = $builder->build();

// Routing
$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    $routes = require 'config/route.php';
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
});
// Get PSR-7 request
$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);
// Dispatch 
$routeInfo = $dispatcher->dispatch(
    $request->getMethod(), 
    $request->getUri()->getPath()
);
switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        $controllerName = Error404::class;
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        $controllerName = Error405::class;
        break;
    case Dispatcher::FOUND:
        $controllerName = $routeInfo[1];
        break;
}
$controller = $container->get($controllerName);
$controller->execute($request);