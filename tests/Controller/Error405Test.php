<?php
declare(strict_types=1);

namespace SimpleMVC\Test\Controller;

use League\Plates\Engine;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Controller\Error405;

final class Error405Test extends TestCase
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
        $this->error = new Error405($this->stubPlates);
        $this->request = $this->createMock(ServerRequestInterface::class);
    }

    public function testExecuteRender405View(): void
    {
        $response = $this->error->execute($this->request);
        $this->assertEquals(405, $response->getStatusCode());
    }
}
