<?php

namespace Base;

use \Base\Router;
use \Psr\Http\Message\RequestInterface as Request;

interface Dispatcher
{
    public function __construct(Router $router);
    public function setBaseUrl($baseUrl);
    public function dispatch(Request $request);
}
