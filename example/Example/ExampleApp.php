<?php

namespace ExampleCo\Example;

class ExampleApp extends \Base\Concrete\App implements \Base\App
{
    public function get(){
        $args = func_get_args();
        return call_user_func_array([$this->router, 'get'], $args);
    }
}
