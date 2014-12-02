<?php

namespace Base;

class InjectorBuilder
{

    protected $di;

    public function __construct(\Auryn\Injector $di = null)
    {
        $di = $di !== null ? : new \Auryn\Provider;
        $this->di = $di;
    }

    public function register(\Base\Interfaces\ServiceRegistererInterface $sr)
    {
        $sr->register($this->di);
        return $this;
    }
    
    public function getDi(){
        return $this->di;
    }

}
