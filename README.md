## SimpleMVC

This is a simple MVC framework for PHP that uses a simple routing system and PSR-7 request messages passed in a controller.

The routing system is based on a simple associative array as follows:

```php
use SimpleMVC\Controller;

return [
    'GET /' => Controller\Home::class, 
];
```

All the controllers are mapped with an HTTP method and a URL path separated by a space character.

All the controllers implement a `ControllerInterface` with only one function `execute($request)`, where `$request` is PSR-7 `ServerRequestInterface`, as follows:

```php
namespace SimpleMVC\Controller;

use Psr\Http\Message\ServerRequestInterface;

interface ControllerInterface
{
    public function execute(ServerRequestInterface $request);
}
```

This project is basically a tutorial for beginners of the [Model-View-Controller](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) architecture.

It has been used in some PHP programming classes of the [ITS ICT Piemonte](http://www.its-ictpiemonte.it/) school in Italy.

**NOTE:** Since this is a tutorial project, the usage of this software in a production environment is discouraged.

### Copyright

The author of this software is [Enrico Zimuel](https://github.com/ezimuel/).

This software is released under the [Apache License](/LICENSE), Version 2.0.