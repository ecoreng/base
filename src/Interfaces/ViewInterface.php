<?php

namespace Base\Interfaces;

interface ViewInterface
{

    public function render($template, array $data = array(), $prefix = null);
}
