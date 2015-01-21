<?php

namespace Base;

interface View
{

    public function render($template, array $data = array());
}
