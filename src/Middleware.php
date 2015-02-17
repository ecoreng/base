<?php

namespace Base;

use \Base\Interfaces\MiddlewareCallableInterface as MiddlewareCallable;
use \Base\App;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

interface Middleware extends MiddlewareCallable
{

    public function setNextMiddleware(MiddlewareCallable $mw);

    public function getNextMiddleware();
    
    public function next(Request $request, Response $response);
}
