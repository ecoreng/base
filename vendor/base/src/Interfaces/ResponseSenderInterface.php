<?php

namespace Base\Interfaces;

interface ResponseSenderInterface
{
    public function setResponse(\Base\AuraResponseProxy $response);
    
    public function send();
}
