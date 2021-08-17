<?php
use SimpleMVC\Controller;

return [
    [ 'GET', '/', Controller\Home::class ],
    [ 'GET', '/hello[/{name}]', [ Controller\Auth::class, Controller\Hello::class ]]
];
