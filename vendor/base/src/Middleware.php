<?php

namespace Base;

abstract class Middleware implements \Base\Interfaces\MiddlewareInterface
{

    protected $app;
    protected $di;
    protected $next;

    public function setApplication(\Base\Interfaces\AppInterface $app)
    {
        $this->app = $app;
    }

    public function getApplication()
    {
        return $this->app;
    }

    public function setInjector(\Auryn\Injector $di)
    {
        $this->di = $di;
    }

    public function getInjector()
    {
        return $this->di;
    }

    public function setNextMiddleware(\Base\Interfaces\MiddlewareCallableInterface $mw = null)
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
