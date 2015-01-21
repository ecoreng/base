<?php

namespace Base\Concrete;

use \Interop\Container\ContainerInterface as Container;
use \Base\Middleware as IMiddleware;
use \Base\App;
use \Base\Interfaces\MiddlewareCallableInterface as MiddlewareCallable;

abstract class Middleware implements IMiddleware
{

    protected $app;
    protected $di;
    protected $next;

    public function setApplication(App $app)
    {
        $this->app = $app;
    }

    public function getApplication()
    {
        return $this->app;
    }

    public function setInjector(Container $di)
    {
        $this->di = $di;
    }

    public function getInjector()
    {
        return $this->di;
    }

    public function setNextMiddleware(MiddlewareCallable $mw = null)
    {
        $this->next = $mw;
    }

    public function getNextMiddleware()
    {
        return $this->next;
    }
    
    public function next(){
        $this->getNextMiddleware()->call();
    }

}
