<?php

namespace ExampleCo\Example;

class ExampleController implements \Base\Interfaces\ControllerInterface
{

    use \Base\ControllerTrait;
    
    protected $request;

    public function getIndex($id)
    {
        //return $this->redirect('http://www.google.com');
        return $this->response('wohoo3: ' . $id);
        //return $this->notFound('not found!');
        //return $this->notFound('dang!');
    }
}
