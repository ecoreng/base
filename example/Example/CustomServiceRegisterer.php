<?php

namespace ExampleCo\Example;

use \Interop\Container\ContainerInterface;

class CustomServiceRegisterer implements \Base\ServiceRegisterer
{

    public function register(ContainerInterface $di)
    {
        $di->set('Base\App', 'ExampleCo\Example\ExampleApp');
    }
}
