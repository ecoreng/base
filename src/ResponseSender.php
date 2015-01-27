<?php

namespace Base;

use Psr\Http\Message\ResponseInterface as Response;

interface ResponseSender
{
    public function setResponse(Response $response);
    
    public function send();
}
