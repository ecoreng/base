<?php

namespace Base\Concrete;

use \Base\Config as IConfig;

class Config implements IConfig
{

    protected $data = [];

    public function __construct(array $data = [])
    {
        $this->setArray($data);
    }

    public function get($key, $default = null)
    {
        if (!isset($this->data[$key])) {
            return $default;
        }
        return $this->data[$key];
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function set($key, $data)
    {
        $this->data[$key] = $data;
    }
    
    public function delete($key)
    {
        unset($this->data[$key]);
    }

    public function setArray(array $data)
    {
        $this->data = array_replace_recursive($this->data, $data);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->delete($offset);
    }
}
