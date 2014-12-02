<?php

namespace Base\Interfaces;

interface ServiceRegistererInterface
{
    
    public function register(\Auryn\Injector $di);
}
