## SimpleMVC

This is a mini MVC PHP framework using [FastRoute](https://github.com/nikic/FastRoute) routing system and [PSR-7](https://www.php-fig.org/psr/psr-7/) standard for HTTP messages.

The routing system uses a PHP configuration file as follows:

```php
use SimpleMVC\Controller;

return [
    ['GET', '/', Controller\Home::class]
];
```

All the controllers are mapped with an HTTP method and a URL path. You can use all the parameters and regex from FastRoute to specify the URL path. Please have a look at the [documentation](https://github.com/nikic/FastRoute/blob/master/README.md) of FastRoute for additional information.

## Dispatch

The dispatch algorithm select the controller to be executed according to HTTP method and URL.
A controller implements a `ControllerInterface` with one function `execute($request)`, where `$request` is PSR-7 `ServerRequestInterface`, as follows:

```php
namespace SimpleMVC\Controller;

use Psr\Http\Message\ServerRequestInterface;

interface ControllerInterface
{
    public function execute(ServerRequestInterface $request);
}
```

This project is used a tutorial for introducing the [Model-View-Controller](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) architecture in modern PHP applications.

This project is used by [Enrico Zimuel](https://www.zimuel.it/) for teaching **PHP programming** at [ITS ICT Piemonte](http://www.its-ictpiemonte.it/) post-diploma school in Italy.

### Copyright

The author of this software is [Enrico Zimuel](https://github.com/ezimuel/) and other [contributors](https://github.com/ezimuel/SimpleMVC/graphs/contributors).

This software is released under the [Apache License](/LICENSE), Version 2.0.
