<?php

namespace Base\Interfaces;

interface AutoloaderInterface
{
    
    public function addPsr4($prefix, $paths, $prepend = false);
}
