<?php

namespace Base;

interface EventEmitter
{

    public function addListener($event, $listener, $priority = 0);

    public function emit($event);
}
