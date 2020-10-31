<?php
declare(strict_types=1);

namespace SimpleMVC\Controller;

use League\Plates\Engine;
use Psr\Http\Message\ServerRequestInterface;

class Hello implements ControllerInterface
{
    protected $plates;

    public function __construct(Engine $plates)
    {
        $this->plates = $plates;
    }

    public function execute(ServerRequestInterface $request)
    {
        $name = $request->getAttribute('name', 'unknown');

        echo $this->plates->render('hello', [
            'name' => ucfirst($name)
        ]);
    }
}
