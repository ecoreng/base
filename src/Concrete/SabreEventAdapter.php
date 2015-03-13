<?php

namespace Base\Concrete;

use \Sabre\Event\EventEmitter as Emitter;

class SabreEventAdapter implements \Base\EventEmitter
{
    protected $emitter;

    public function __construct(Emitter $emitter = null)
    {
        if ($emitter) {
            $this->emitter = $emitter;
        } else {
            $this->emitter = new Emitter;
        }
    }

    public function getInstance()
    {
        return $this->emitter;
    }

    public function addListener($event, $listener, $priority = 0)
    {
        return $this->emitter->on($event, $listener, $priority);
    }

    public function emit($event, array $args = [])
    {
        array_unshift($args, $event);
        return call_user_func_array([$this->emitter, 'emit'], $args);
    }
}
