<?php
declare(strict_types=1);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use DI\ContainerBuilder;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use SimpleMVC\Controller\Error404;
use SimpleMVC\Controller\Error405;

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

// Build the PSR-7 server request
$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);
$request = $creator->fromGlobals();

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
        if (isset($routeInfo[2])) {
            foreach ($routeInfo[2] as $name => $value) {
                $request = $request->withAttribute($name, $value);
            }
        }
        break;
}
$controller = $container->get($controllerName);
$controller->execute($request);