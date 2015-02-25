<?php

namespace Base\Concrete;

use League\Event\Emitter;

class LeagueEventAdapter implements \Base\EventEmitter
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
        return $this->emitter->addListener($event, $listener, $priority);
    }

    public function emit($event)
    {
        $args = func_get_args();
        return call_user_func_array([$this->emitter, 'emit'], $args);
    }
}
