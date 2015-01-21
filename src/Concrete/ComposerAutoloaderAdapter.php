<?php

namespace Base\Concrete;

use \Composer\Autoload\ClassLoader as Loader;

class ComposerAutoloaderAdapter implements \Base\Autoloader
{

    protected $autoloader;

    public function __construct(Loader $autoloader)
    {
        $this->autoloader = $autoloader;
    }

    public function setAutoloader(Loader $autoloader)
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
