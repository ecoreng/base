<?php

namespace Base\Interfaces;

interface DispatcherInterface
{
    
    public function dispatch(\Base\Interfaces\RequestInterface $request);
}
