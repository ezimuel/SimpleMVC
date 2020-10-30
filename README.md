## SimpleMVC

**SimpleMVC** is a micro MVC framework for PHP using [FastRoute](https://github.com/nikic/FastRoute), [PHP-DI](https://php-di.org/), [Plates](https://platesphp.com/) and [PSR-7](https://www.php-fig.org/psr/psr-7/) standard for HTTP messages.

This framework is mainly used as tutorial for introducing the [Model-View-Controller](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) architecture in modern PHP applications.

This project is used for the course **PHP Programming** by [Enrico Zimuel](https://www.zimuel.it/) at [ITS ICT Piemonte](http://www.its-ictpiemonte.it/),
an Higher Education School specialized in Information and communications technology in Italy.

![ITS ICT Piemonte](public/img/its-torino.png)

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

A controller implements a `ControllerInterface` with one function `execute($request)`, where `$request` is PSR-7 `ServerRequestInterface`, as follows:

```php
namespace SimpleMVC\Controller;

use Psr\Http\Message\ServerRequestInterface;

interface ControllerInterface
{
    public function execute(ServerRequestInterface $request);
}
```

## Dependecy injection container

All the dependencies are managed using the [PHP-DI](https://php-di.org/) project.

The dependency injection container is configured in [config/container.php](config/container.php) file.


### Copyright

The author of this software is [Enrico Zimuel](https://github.com/ezimuel/) and other [contributors](https://github.com/ezimuel/SimpleMVC/graphs/contributors).

This software is released under the [Apache License](/LICENSE), Version 2.0.
