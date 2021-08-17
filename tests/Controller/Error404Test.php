<?php
declare(strict_types=1);

namespace SimpleMVC\Test\Controller;

use League\Plates\Engine;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Controller\Error404;

final class Error404Test extends TestCase
{
    /** @var Engine */
    private $stubPlates;

    /** @var ControllerInterface */
    private $error;
    
    /** @var ServerRequestInterface&MockObject */
    private $request;

    public function setUp(): void
    {
        $this->stubPlates = $this->createMock(Engine::class);
        $this->error = new Error404($this->stubPlates);
        $this->request = $this->createMock(ServerRequestInterface::class);
    }

    public function testExecuteRender404View(): void
    {
        $response = $this->error->execute($this->request);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
