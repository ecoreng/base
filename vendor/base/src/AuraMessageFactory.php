<?php

namespace Base;

use \Base\Interfaces\ServerSideMessageFactoryInterface as MessageFactory;
use \Base\AuraRequestProxy as Request;
use \Base\AuraResponseProxy as Response;
use \Aura\Web\WebFactory as WebFactory;

class AuraMessageFactory implements MessageFactory
{

    protected $factory;

    public function __construct(WebFactory $webFactory)
    {
        $this->factory = $webFactory;
    }

    public function newIncomingRequest()
    {
        return new Request($this->factory->newRequest());
    }

    public function newOutgoingResponse()
    {
        return new Response($this->factory->newResponse());
    }

}
