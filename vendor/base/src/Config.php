<?php

namespace Base;

class Config extends \ArrayObject implements \Base\Interfaces\ConfigInterface
{

    public function __construct($config)
    {
        parent::__construct($config);
    }

    public function get($key)
    {
        if (isset($this[$key])) {
            return $this[$key];
        }
        return null;
    }

    public function set($key, $value)
    {
        $this[$key] = $value;
    }

}
