<?php
/**
 * SimpleMVC
 *
 * @link      http://github.com/simplemvc
 * @copyright Copyright (c) Enrico Zimuel (https://www.zimuel.it)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */
declare(strict_types=1);

namespace SimpleMVC\Response;

use Psr\Http\Message\ResponseInterface;

class SapiEmitter
{
    public static function emit(ResponseInterface $response): void
    {
        // headers
        $headers = $response->getHeaders();
        foreach ($headers as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        $statusCode = $response->getStatusCode();
        $reasonPhrase = $response->getReasonPhrase();

        // status code line
        header(sprintf(
            'HTTP/%s %d%s',
            $response->getProtocolVersion(),
            $statusCode,
            empty($reasonPhrase) ? '' : ' ' . $reasonPhrase
        ), true, $statusCode);

        // body
       $body = (string) $response->getBody();
       if (!empty($body)) {
           header(sprintf("Content-Length: %d", strlen($body)));
       }
       echo $body;
    }
}