<?php

namespace Base\Concrete;

use \Base\ResponseSender as Sender;
use \Psr\Http\Message\OutgoingResponseInterface as Response;
use \Aura\Web\ResponseSender;

class AuraResponseSenderAdapter implements Sender
{

    protected $response;
    protected $sender;

    public function setResponse(Response $response)
    {
        $this->response = $response;
        $this->sender = new ResponseSender($response->getInstance());
    }

    public function send()
    {
        call_user_func($this->sender);
    }

}
