<?php

namespace ExampleCo\Example;

class CustomMiddleware extends \Base\Concrete\Middleware implements \Base\Middleware
{

    public function call()
    {
        $this->app->addRoute('GET', '/aaa', function(){return 'wee';});
        $this->next();
    }

}
