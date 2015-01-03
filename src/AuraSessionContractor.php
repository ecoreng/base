<?php

namespace Base;

class AuraSessionContractor implements \Base\Interfaces\SessionInterface
{

    protected $activeSegment;
    protected $session;

    public function __construct(\Aura\Session\Session $auraSession)
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

    public function start(){
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

}
