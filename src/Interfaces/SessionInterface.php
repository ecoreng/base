<?php

namespace Base\Interfaces;

interface SessionInterface
{

    public function get($key, $alt = null);

    public function getFlash($key, $alt = null);

    public function getFlashNext($key, $alt = null);
}
