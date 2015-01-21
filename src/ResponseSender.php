<?php

namespace Base;

use \Psr\Http\Message\OutgoingResponseInterface as Response;

interface ResponseSender
{
    public function setResponse(Response $response);
    
    public function send();
}
