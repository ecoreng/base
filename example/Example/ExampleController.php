<?php

namespace ExampleCo\Example;

class ExampleController implements \Base\Interfaces\ControllerInterface
{

    use \Base\ControllerTrait;

    protected $request;

    public function getIndex()
    {
        //return $this->redirect('http://www.google.com');
        return $this->response('getIndex from ExampleController; via: ' . $this->request->getMethod());
        //return $this->notFound('not found!');
    }

    public function getTest($id)
    {
        return $this->response('getTest from ExampleController, id: ' . $id . '; via: ' . $this->request->getMethod());
    }

    public function getTestView()
    {
        return $this->render(null, ['test-data' => 'successful'], 200, 'application/json');
    }

}
