<?php
declare(strict_types=1);

namespace SimpleMVC\Test\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Controller\Auth;

final class AuthTest extends TestCase
{
    /** @var ContainerInterface&MockObject */
    private $container;

    /** @var ControllerInterface */
    private $auth;
    
    /** @var ServerRequestInterface&MockObject */
    private $request;

    /** @var string[] */
    private $config;

    public function setUp(): void
    {
        $this->config = [
            'username' => 'test',
            'password' => 'password'
        ];
        $this->container = $this->createMock(ContainerInterface::class);
        $this->container->method('get')
            ->with($this->equalTo('authentication'))
            ->willReturn($this->config);

        $this->auth = new Auth($this->container);
        $this->request = $this->createMock(ServerRequestInterface::class);
    }

    public function testAuthWithValidCredentials(): void
    {
        $this->request->method('getHeader')
            ->with($this->equalTo('Authorization'))
            ->willReturn([sprintf(
                'Basic %s',
                base64_encode($this->config['username'] . ':' . $this->config['password'])
            )]);

        $response = $this->auth->execute($this->request);
        $this->assertEquals(null, $response);
    }

    public function testAuthWithInvalidCredentials(): void
    {
        $this->request->method('getHeader')
            ->with($this->equalTo('Authorization'))
            ->willReturn([sprintf(
                'Basic %s',
                base64_encode('foo:bar')
            )]);

        $response = $this->auth->execute($this->request);

        $this->assertNotEmpty($response);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('Basic realm="test"', $response->getHeader('WWW-Authenticate')[0]);
    }

    public function testAuthWithoutCredentials(): void
    {
        $response = $this->auth->execute($this->request);
        
        $this->assertNotEmpty($response);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('Basic realm="test"', $response->getHeader('WWW-Authenticate')[0]);
    }
}
