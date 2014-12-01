<?php

namespace ExampleCo\Example;

class CustomServiceRegisterer implements \Base\Interfaces\ServiceRegistererInterface
{


    public function register(\Auryn\Injector $di)
    {

        $di->alias('\Base\Interfaces\AppInterface', '\ExampleCo\Example\ExampleApp');
        
        
    }
}
