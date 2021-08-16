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

use Nyholm\Psr7\Response;

/**
 * This is an empty class that extends Nyholm\Psr7\Response
 * used to halt the execution flow of SimpleMVC
 */
class HaltResponse extends Response
{

}