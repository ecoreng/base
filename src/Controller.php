<?php

namespace Base;

use \Base\View;
use \Psr\Http\Message\OutgoingResponseInterface as Response;
use \Psr\Http\Message\IncomingRequestInterface  as Request;

interface Controller
{

    public function setView(View $view);

    public function setResponse(Response $response);

    public function setRequest(Request$request);
}
