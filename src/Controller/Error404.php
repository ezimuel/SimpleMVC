<?php
declare(strict_types=1);

namespace SimpleMVC\Controller;

use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Error404 implements ControllerInterface
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
            404,
            [],
            $this->plates->render('404')
        );
    }
}
