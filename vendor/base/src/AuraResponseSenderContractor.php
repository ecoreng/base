<?php

namespace Base;

class AuraResponseSenderContractor implements \Base\Interfaces\ResponseSenderInterface
{
    
    protected $response;
    protected $sender;
    
    public function setResponse(\Base\Interfaces\ResponseInterface $response)
    {
        $this->response = $response;
        $this->sender = new \Aura\Web\ResponseSender($response->getInstance());
    }

    public function send()
    {
        call_user_func($this->sender);
    }
}
