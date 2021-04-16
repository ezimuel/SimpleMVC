## SimpleMVC

[![Build status](https://github.com/ezimuel/SimpleMVC/workflows/PHP%20test/badge.svg)](https://github.com/ezimuel/SimpleMVC/actions)

**SimpleMVC** is a micro MVC framework for PHP using [FastRoute](https://github.com/nikic/FastRoute), [PHP-DI](https://php-di.org/), [Plates](https://platesphp.com/) and [PSR-7](https://www.php-fig.org/psr/psr-7/) standard for HTTP messages.

This framework is mainly used as tutorial for introducing the [Model-View-Controller](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) architecture in modern PHP applications.

This project is used in the course **PHP Programming** by [Enrico Zimuel](https://www.zimuel.it/) at [ITS ICT Piemonte](http://www.its-ictpiemonte.it/),
an Higher Education School specialized in Information and Communications Technology in [Turin](https://en.wikipedia.org/wiki/Turin) (Italy).

## Quickstart

You can install the SimpleMVC framework with the following command:

```
composer create-project ezimuel/simple-mvc
```

This will create a `simple-mvc` folder containing the skeleton of a web application.
You can execute the application using the PHP internal web server, as follows:

```
php -S 0.0.0.0:8080 -t public
```

The application will be executed at [http://localhost:8080](http://localhost:8080).

## Routing system

The routing system uses a PHP configuration file as follows ([config/route.php](config/route.php)):

```php
use SimpleMVC\Controller;

return [
    ['GET', '/', Controller\Home::class]
];
```

A route is an element of the array with an HTTP method, a URL and a Controller class to be executed. 
The URL can be specified using the [FastRoute syntax](https://github.com/nikic/FastRoute/blob/master/README.md) syntax.

## Dispatch

The dispatch selects the controller to be executed according to HTTP method and URL.

The dispatch is reported in [public/index.php](public/index.php) front controller.

A controller implements a `ControllerInterface` that contains only one function `execute($request)`,
where `$request` is a PSR-7 `ServerRequestInterface` object:

```php
namespace SimpleMVC\Controller;

use Psr\Http\Message\ServerRequestInterface;

interface ControllerInterface
{
    public function execute(ServerRequestInterface $request);
}
```

The execute function can do anything: render a web page, output a JSON string, 
manipulate the HTTP request and send it to another Controller and so on.

We did not define a type response by design. We would like to offer a simple architecture
with the freedom to manage different use cases.

## A simple render controller

As we discussed before the Controller can implement anything. For instance, if you want
to render a web page you can use the Plates engine stored in the container as follows:

```php
use League\Plates\Engine;
use Psr\Http\Message\ServerRequestInterface;

class Home implements ControllerInterface
{
    protected $plates;

    public function __construct(Engine $plates)
    {
        $this->plates = $plates;
    }

    public function execute(ServerRequestInterface $request)
    {
        echo $this->plates->render('home');
    }
}
```

This [Home](src/Controller/Home.php) controller requires the `League\Plates\Engine` in the constructor and uses it
to render the [src/View/home.php](src/View/home.php) template.

Using the DI pattern we don't need to pass it explicitly (e.g. using a [Factory](https://en.wikipedia.org/wiki/Factory_method_pattern)).
Every dependency is resolved automatically by [PHP-DI](https://php-di.org/).

## Dependecy injection container

All the dependencies are managed using the [PHP-DI](https://php-di.org/) project.

The dependency injection container is configured in [config/container.php](config/container.php) file.


### Copyright

The author of this software is [Enrico Zimuel](https://github.com/ezimuel/) and other [contributors](https://github.com/ezimuel/SimpleMVC/graphs/contributors).

This software is released under the [Apache License](/LICENSE), Version 2.0.
