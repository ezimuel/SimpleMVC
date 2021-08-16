<?php
declare(strict_types=1);

namespace SimpleMVC\Controller;

use DI\NotFoundException;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Exception\InvalidConfigException;
use SimpleMVC\Response\HaltResponse;

class Auth implements ControllerInterface
{
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var string
     */
    protected $realm;

    public function __construct(ContainerInterface $container)
    {
        try {
            $auth = $container->get('authentication');
        } catch (NotFoundException $e) {
            throw new InvalidConfigException(
                'The [authentication] key is missing in config/app.php'
            );
        }
        $this->username = $auth['username'] ?? null;
        $this->password = $auth['password'] ?? null;
        $this->realm    = $auth['realm'] ?? 'test';

        if (is_null($this->username) || is_null($this->password)) {
            throw new InvalidConfigException(
                'The [authentication][username] and [authentication][password] are missing in config/app.php'
            );
        }
    }

    /**
     * Implements Basic Access Authentication
     * 
     * @see https://en.wikipedia.org/wiki/Basic_access_authentication
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response = null): ?ResponseInterface
    {
        $auth = $request->getHeader('Authorization');
        if (empty($auth)) {
            return new HaltResponse(401, ['WWW-Authenticate' => "Basic realm=\"{$this->realm}\""]);
        }
        list (, $credential) = explode('Basic ', $auth[0]);
        list($username, $password) = explode(':', base64_decode($credential));
        if ($username !== $this->username || $password !== $this->password) {
            return new HaltResponse(401, ['WWW-Authenticate' => "Basic realm=\"{$this->realm}\""]);
        }
        return $response;
    }
}