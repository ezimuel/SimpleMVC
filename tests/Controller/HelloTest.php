<?php
declare(strict_types=1);

namespace SimpleMVC\Test\Controller;

use League\Plates\Engine;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\Hello;

final class HelloTest extends TestCase
{
    /** @var Engine */
    private $plates;

    /** @var ControllerInterface */
    private $home;
    
    /** @var object */
    private $request;

    public function setUp(): void
    {
        $this->plates = new Engine('src/View');
        $this->home = new Hello($this->plates);
        $this->request = $this->createMock(ServerRequestInterface::class);
    }

    public function testExecuteRenderHomeView(): void
    {
        $name = 'test';
        $this->request->method('getAttribute')
            ->with($this->equalTo('name'))
            ->willReturn($name);

        $this->expectOutputString($this->plates->render('hello', ['name' => ucfirst($name)]));
        $this->home->execute($this->request);
    }
}
