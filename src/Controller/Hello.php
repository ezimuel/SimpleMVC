<?php
declare(strict_types=1);

namespace SimpleMVC\Controller;

use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Hello implements ControllerInterface
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
        $name = $request->getAttribute('name', 'unknown');

        return new Response(
            200, 
            [],
            $this->plates->render('hello', [
                'name' => ucfirst($name)
            ])
        );
    }
}
