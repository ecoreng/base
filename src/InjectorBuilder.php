<?php

namespace Base;

use \Base\Interfaces\ServiceRegistererInterface as Services;

class InjectorBuilder
{

    protected $di;
    protected $registerers;

    public function __construct(\Auryn\Injector $di = null)
    {
        $this->setDi($di);
    }

    public function register()
    {
        $registerers = func_get_args();
        $this->registerers = $registerers;

        foreach ($this->registerers as $reg) {
            if (!($reg instanceof Services)) {
                throw new \Exception(
                    get_class($reg) . ' is not an instance of \Base\Interfaces\ServiceRegistererInterface'
                );
            }
            $reg->register($this->di);
        }
        return $this;
    }

    public function setDi(\Auryn\Injector $di = null)
    {
        $di = $di !== null ? : new \Auryn\Provider;
        $this->di = $di;
    }

    public function getDi()
    {
        return $this->di;
    }
}
