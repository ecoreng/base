<?php

namespace Base;

class InjectorBuilder
{

    protected $di;
    protected $registerers;

    public function __construct()
    {
        $registerers = func_get_args();
        $this->registerers = $registerers;
    }

    public function setDi(\Auryn\Injector $di = null)
    {
        $di = $di !== null ? : new \Auryn\Provider;
        $this->di = $di;
    }

    public function register(\Base\Interfaces\ServiceRegistererInterface $sr)
    {
        if ($this->di === null) {
            $this->setDi();
        }
        $sr->register($this->di);
        return $this;
    }

    public function getDi()
    {
        return $this->di;
    }

    public function getApp()
    {
        foreach ($this->registerers as $reg) {
            $this->register($reg);
        }
        $di = $this->getDi();
        return $di->make('\Base\Interfaces\AppInterface');
    }

    public function getComponents()
    {
        return [$this->getApp(), $this->getDi()];
    }

}
