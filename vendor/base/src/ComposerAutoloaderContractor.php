<?php

namespace Base;

class ComposerAutoloaderContractor implements \Base\Interfaces\AutoloaderInterface
{

    protected $autoloader;

    public function __construct(\Composer\Autoload\ClassLoader $autoloader)
    {
        $this->autoloader = $autoloader;
    }

    public function addPsr4($prefix, $paths, $prepend = false)
    {
        return $this->autoloader->addPsr4($prefix, $paths, $prepend);
    }

    public function __call($name, array $arguments)
    {
        return call_user_func_array([$this->autoloader, $name], $arguments);
    }

}
