<?php

namespace Base;

interface Autoloader
{
    
    public function addPsr4($prefix, $paths, $prepend = false);
}
