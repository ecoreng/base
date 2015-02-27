<?php

namespace Base\Concrete;

use \Base\Middleware as IMiddleware;
use \Base\Interfaces\MiddlewareCallableInterface as MiddlewareCallable;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

abstract class Middleware implements IMiddleware
{
    protected $next;

    public function setNextMiddleware(MiddlewareCallable $mw = null)
    {
        $this->next = $mw;
    }

    public function getNextMiddleware()
    {
        return $this->next;
    }

    public function next(Request $request, Response $response)
    {
        return $this->getNextMiddleware()->call($request, $response);
    }
}
