<?php

namespace Base;

use \Base\Interfaces\ResponseSenderInterface as Sender;
use \Psr\Http\Message\OutgoingResponseInterface as Response;

class AuraResponseSenderContractor implements Sender
{

    protected $response;
    protected $sender;

    public function setResponse(Response $response)
    {
        $this->response = $response;
        $this->sender = new \Aura\Web\ResponseSender($response->getInstance());
    }

    public function send()
    {
        call_user_func($this->sender);
    }

}
