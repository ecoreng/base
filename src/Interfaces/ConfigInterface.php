<?php

namespace Base\Interfaces;

interface ConfigInterface
{
    public function __construct($config);
    public function get($key);
    public function set($key, $value);
}
