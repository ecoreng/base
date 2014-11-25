<?php

namespace ExampleCo\Example;

class ExampleController implements \Base\Interfaces\ControllerInterface
{

    use \Base\ControllerTrait;
    
    protected $request;

    public function __construct(\Base\Interfaces\RequestInterface $request)
    {
        $this->request = $request;
        var_dump($request);
    }

    public function getDemo()
    {
        //return $this->redirect('http://www.google.com');
        return $this->response('wohoo2');
        //return $this->notFound('not found!');
        //return $this->notFound('dang!');
    }
}
