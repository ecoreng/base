<?php

namespace Base;

interface Config extends \IteratorAggregate, \ArrayAccess
{
    public function __construct(array $data = []);
    public function get($key, $default = null);
    public function set($key, $data);
    public function setArray(array $data);
    public function has($key);
    public function delete($key);
}
