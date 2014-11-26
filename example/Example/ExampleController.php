<?php

namespace ExampleCo\Example;

class ExampleController implements \Base\Interfaces\ControllerInterface
{

    use \Base\ControllerTrait;
    
    protected $request;

    public function __construct()
    {
        
    }

    public function getDemo()
    {
        //return $this->redirect('http://www.google.com');
        return $this->response('wohoo2');
        //return $this->notFound('not found!');
        //return $this->notFound('dang!');
    }
}
