<?php

namespace Base;

class AuraResponseSenderContractor implements \Base\Interfaces\ResponseSenderInterface
{
    
    protected $response;
    protected $sender;
    
    public function setResponse(\Psr\Http\Message\OutgoingResponseInterface $response)
    {
        $this->response = $response;
        $this->sender = new \Aura\Web\ResponseSender($response->getInstance());
    }

    public function send()
    {
        call_user_func($this->sender);
    }
}
