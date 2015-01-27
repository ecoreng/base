<?php

namespace Base\Interfaces;

interface ServerSideMessageFactoryInterface
{
    public function newRequest();
    public function newResponse();
    public function resetFactory(array $environment);
}
