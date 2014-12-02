<?php

namespace Base\Interfaces;

interface ResponseSenderInterface
{
    public function setResponse(\Psr\Http\Message\OutgoingResponseInterface $response);
    
    public function send();
}
