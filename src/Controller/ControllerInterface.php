<?php
/**
 * SimpleMVC
 *
 * @link      http://github.com/simplemvc
 * @copyright Copyright (c) Enrico Zimuel (https://www.zimuel.it)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */
declare(strict_types=1);

namespace SimpleMVC\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ControllerInterface
{
    public function execute(ServerRequestInterface $request, ResponseInterface $response = null): ?ResponseInterface;
}
