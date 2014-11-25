<?php

namespace ExampleCo\Example;

class ExampleController implements \Base\Interfaces\ControllerInterface
{

    use \Base\ControllerTrait;
    
    protected $session;

    public function __construct(\Base\Interfaces\SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getDemo()
    {
        //return $this->redirect('http://www.google.com');
        return $this->response('wohoo2');
        //return $this->notFound('not found!');
        //return $this->notFound('dang!');
    }
}
