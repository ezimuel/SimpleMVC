<?php
declare(strict_types=1);

namespace SimpleMVC\Test\Controller;

use League\Plates\Engine;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Controller\Home;

final class HomeTest extends TestCase
{
    /** @var Engine */
    private $plates;

    /** @var ControllerInterface */
    private $home;
    
    /** @var ServerRequestInterface&MockObject */
    private $request;

    public function setUp(): void
    {
        $this->plates = new Engine('src/View');
        $this->home = new Home($this->plates);
        $this->request = $this->createMock(ServerRequestInterface::class);
    }

    public function testExecuteRenderHomeView(): void
    {
        $response = $this->home->execute($this->request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->plates->render('home'), (string) $response->getBody());
    }
}
