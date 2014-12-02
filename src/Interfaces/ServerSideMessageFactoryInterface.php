<?php

namespace Base\Interfaces;

interface ServerSideMessageFactoryInterface
{
    public function newIncomingRequest();
    public function newOutgoingResponse();
    public function resetFactory(array $environment);
}
