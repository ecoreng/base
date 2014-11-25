<?php

namespace Base;

use \Base\Interfaces\ServerSideMessageFactoryInterface as MessageFactory;
use \Base\AuraRequestContractor as Request;
use \Base\AuraResponseContractor as Response;
use \Aura\Web\WebFactory as WebFactory;

class AuraMessageFactoryContractor implements MessageFactory
{

    protected $realFactory;
    protected $virtualFactory;

    public function __construct(WebFactory $webFactory)
    {
        $this->realFactory = $webFactory;
    }

    public function setFactory(WebFactory $webFactory, $type = 'real')
    {
        if ($type !== 'real' && $type !== 'virtual') {
            throw new \Exception('Invalid factory type');
        }
        $this->{$type . "Factory"} = $webFactory;
    }

    public function newIncomingRequest($type = 'real')
    {
        if ($type !== 'real' && $type !== 'virtual') {
            throw new \Exception('Invalid factory type');
        }
        return new Request( $this->{$type . "Factory"}->newRequest());
    }

    public function newOutgoingResponse($type = 'real')
    {
        if ($type !== 'real' && $type !== 'virtual') {
            throw new \Exception('Invalid factory type');
        }
        return new Response( $this->{$type . "Factory"}->newResponse());
    }

}
