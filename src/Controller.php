<?php

namespace Base;

use \Base\View;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\RequestInterface  as Request;

interface Controller
{

    public function setView(View $view);

    public function setResponse(Response $response);

    public function setRequest(Request$request);
}
