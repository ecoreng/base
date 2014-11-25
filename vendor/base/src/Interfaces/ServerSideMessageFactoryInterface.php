<?php

namespace Base\Interfaces;

interface ServerSideMessageFactoryInterface
{
    public function newIncomingRequest();
    public function newOutgoingResponse();
}
