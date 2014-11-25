<?php

namespace Base\Interfaces;

interface ResponseSenderInterface
{
    public function setResponse(\Base\Interfaces\ResponseInterface $response);
    
    public function send();
}
