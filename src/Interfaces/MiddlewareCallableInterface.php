<?php

namespace Base\Interfaces;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

interface MiddlewareCallableInterface
{

    public function call(Request $request, Response $response);
}
