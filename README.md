## SimpleMVC

[![Build status](https://github.com/ezimuel/SimpleMVC/workflows/PHP%20test/badge.svg)](https://github.com/ezimuel/SimpleMVC/actions)

**SimpleMVC** is a micro MVC framework for PHP based on the [KISS](https://en.wikipedia.org/wiki/KISS_principle) principle:
> "Keep It Simple, Stupid"

The goal of this project is to offer a simple and fast [MVC](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
framework for PHP applications.

It uses the [dependency injection](https://en.wikipedia.org/wiki/Dependency_injection) principle to manage
the dependencies between classes and the [FastRoute](https://github.com/nikic/FastRoute) library
for implementing the routing system.

SimpleMVC uses the following PSR standards, from the [PHP-FIG](https://www.php-fig.org/) initiative:

- [PSR-11](https://www.php-fig.org/psr/psr-11/) for DI Container;
- [PSR-7](https://www.php-fig.org/psr/psr-7/) for HTTP message;
- [PSR-3](https://www.php-fig.org/psr/psr-3/) for logging;

This project is used in the course **PHP Programming** by [Enrico Zimuel](https://www.zimuel.it/) at [ITS ICT Piemonte](http://www.its-ictpiemonte.it/),
an Higher Education School specialized in Information and Communications Technology in [Turin](https://en.wikipedia.org/wiki/Turin) (Italy).

## Quickstart

You can install a SimpleMVC skeleton application using the following command:

```
composer create-project simplemvc/skeleton
```

This will create a `skeleton` folder containing a basic web application.
You can execute the application using the PHP internal web server, as follows:

```
php -S 0.0.0.0:8080 -t public
```

The application will be executed at [http://localhost:8080](http://localhost:8080).

This skeleton uses [PHP-DI](https://php-di.org/) as DI container and [Plates](https://platesphp.com/)
as template engine.

## Configuration

The application is configured using the ([config/app.php](config/app.php)) file:

```php
return [
    'routing' => [
        'routes' => require 'route.php',
        'cache'  => true
    ],
    'container' => require 'container.php',
    'error' => [
        '404' => Error404::class,
        '405' => Error405::class
    ],
    'bootstrap' => function(ContainerInterface $c) {
       // Put here the code to bootstrap, if any
       // e.g. a database or ORM initialization
    }
];
```
Each section contains the configuration of the routing system (`route`), the DI Container (`container`),
the error based on HTTP status code (`error`) and the `boostrap`, if present.

## Routing system

The routing system uses a PHP configuration file as follows ([config/route.php](config/route.php)):

```php
use SimpleMVC\Controller;

return [
    ['GET', '/', Controller\Home::class]
];
```

A route is an element of the array with an HTTP method, a URL and a Controller class to be executed. 
The URL can be specified using the FastRoute [syntax](https://github.com/nikic/FastRoute/blob/master/README.md).

## Controller

Each controller in SimpleMvc implements the `ControllerInterface`, as follows:

```php
namespace SimpleMVC\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ControllerInterface
{
    public function execute(
        ServerRequestInterface $request, 
        ResponseInterface $response = null
    ): ?ResponseInterface;
}
```

The `execute()` function accepts two parameters, the `$request` and the optional `$response`.
These are [PSR-7](https://www.php-fig.org/psr/psr-7/) HTTP request and response.
The request is mandatory to execute a controller. The response is typically used when you need to
execute a pipeline of multiple controllers, where you may want to pass the response from one controller
to another.

The return of the `execute()` function can be null (?) or a PSR-7 Response. 
For instance, the `Home` controller reported in the skeleton application is as follows:

```php
namespace SimpleMVC\Controller;

use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Home implements ControllerInterface
{
    /**
     * @var Engine
     */
    protected $plates;

    public function __construct(Engine $plates)
    {
        $this->plates = $plates;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response = null): ?ResponseInterface
    {
        return new Response(
            200,
            [],
            $this->plates->render('home')
        );
    }
}
```

The `execute()` function returns a PSR-7 `Nyholm\Psr7\Response` object using the [nyholm/psr7](https://github.com/Nyholm/psr7)
project.

The controller can also return `null`, for instance when it implements an authentication check or when it decorates the request.
This is typically used when executing a controller pipeline.
## Execute a controller pipeline

In the route configuration file you can specify an array of controller to be executed.
For instance, you can speficy that the `Hello` controller needs HTTP authentication and you
can implement the logic in a separate `Auth` controller.
The `config/route.php` configuration can be as follows:

```php
use SimpleMVC\Controller;

return [
    ['GET', '/', [Controller\Auth::class, Controller\Hello::class]]
];
```

The third element of the array is an array itself, containing the list of controller to be executed.
The order of execution is the same of the array, that means `Auth` will be executed first and `Home` after.

If you want, you can halt the execution flow of SimpleMVC returning a [HaltResponse](src/Response/HaltResponse.php).
This response is just an empty class that extends a PSR-7 request to inform SimpleMVC to **stop the execution**.

We provided an example of [Controller\Auth](src/Controller/Auth.php) using a
[Basic Access Authentication](https://en.wikipedia.org/wiki/Basic_access_authentication).


## Dependecy injection container

All the dependencies are managed using the [PHP-DI](https://php-di.org/) project.

The dependency injection container is configured in [config/container.php](config/container.php) file.

## Front controller

A SimpleMVC application is executed using the `public/index.php` file.
All the HTTP requests pass from this file, that is called **Front controller**.

![MVC diagram](./mvc.png)

An example of front controller is as follows:

```php
declare(strict_types=1);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use DI\ContainerBuilder;
use SimpleMVC\App;

$builder = new ContainerBuilder();
$builder->addDefinitions('config/container.php');
$container = $builder->build();

$app = new App($container, require 'config/app.php');
$app->bootstrap();
$app->dispatch();
```

In this file we build the CI container, reading from the [config/container.php](config/container.php) file
and we pass this container to the [SimpleMVC\App]() class, along with the [config/app.php](config/app.php)
configuration file.

Then, we execute the `bootstrap` function if we have specified a callable in the `bootstrap` field of `config/app.php`.
Last, we execute the `dispatch` function that performs the dispatch of the Controller using the `config/route.php`
configuration.

### Copyright

The author of this software is [Enrico Zimuel](https://github.com/ezimuel/) and other [contributors](https://github.com/ezimuel/SimpleMVC/graphs/contributors).

This software is released under the [Apache License](/LICENSE), Version 2.0.
