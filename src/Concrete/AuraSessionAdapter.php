<?php

namespace Base\Concrete;

use \Base\Session;
use \Aura\Session\Session as AuraSession;

class AuraSessionAdapter implements Session
{

    protected $activeSegment;
    protected $session;

    public function __construct(AuraSession $auraSession)
    {
        $this->session = $auraSession;
    }

    public function get($key, $default = null)
    {
        return $this->getSegment()->get($key, $default);
    }

    public function set($key, $value)
    {
        return $this->getSegment()->get($key, $value);
    }

    public function getFlash($key, $alt = null)
    {
        return $this->getSegment()->getFlash($key, $alt);
    }

    public function getFlashNext($key, $alt = null)
    {
        return $this->getSegment()->getFlashNext($key, $alt);
    }

    public function start()
    {
        return $this->session->start();
    }

    public function __destruct()
    {
        $this->session->commit();
    }

    protected function setSegment($segment = '\Base\App')
    {
        $this->activeSegment = $this->session->getSegment($segment);
        return $this->activeSegment;
    }

    protected function getSegment()
    {
        if ($this->activeSegment == null) {
            $this->setSegment();
        }
        return $this->activeSegment;
    }

    // delegate all other calls to instance
    public function __call($name, $args)
    {
        return call_user_func_array([$this->getSegment(), $name], $args);
    }

    public function __set($name, $value)
    {
        return $this->session->{$name} = $value;
    }

    public function __get($attr)
    {
        return $this->session->$attr;
    }

    public function getInstance()
    {
        return $this->session;
    }

}
