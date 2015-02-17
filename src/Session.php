<?php

namespace Base;

interface Session
{
    public function start();

    public function set($key, $value);

    public function get($key, $alt = null);

    public function getFlash($key, $alt = null);

    public function getFlashNext($key, $alt = null);
}
