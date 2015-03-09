<?php

namespace Base\Concrete;

use \Base\View;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\RequestInterface as Request;
use \Phly\Http\Stream;

trait ControllerTrait
{

    protected $view;
    protected $responseObject;
    protected $requestObject;
    protected $count = 0;
    public function setView(View $view)
    {
        $this->view = $view;
    }

    public function setResponse(Response $response)
    {
        $this->responseObject = $response;
    }

    public function setRequest(Request $request)
    {
        $this->requestObject = $request;
    }
    
    public function getRequest()
    {
        return $this->requestObject;
    }
    
    public function getResponse()
    {
        return $this->responseObject;
    }

    protected function render($template, $data = [], $status = 200, $contentType = 'text/html; charset=utf-8')
    {
        $view = $this->getView();
        return $this->response($view->render($template, $data), $status, $contentType);
    }

    protected function getView()
    {
        if ($this->viewReady()) {
            return $this->getViewObject();
        } else {
            throw new \Exception('View not set.');
        }
    }

    protected function getViewObject()
    {
        return $this->view;
    }

    protected function viewReady()
    {
        return $this->getViewObject() instanceof View;
    }

    protected function response($body = '', $status = 200, $contentType = 'text/html; charset=utf-8')
    {
        $response = $this->responseObject;
        $response->getBody()
                ->write($body);
        $response = $response->withStatus($status)
                ->withHeader('Content-type', $contentType);
        return $response;
    }

    protected function error($body = '', $status = 500, $contentType = 'text/html; charset=utf-8')
    {
        return $this->response($body, $status, $contentType);
    }

    protected function notFound($body = '', $status = 404, $contentType = 'text/html; charset=utf-8')
    {
        return $this->response($body, $status, $contentType);
    }

    protected function redirect($url, $status = 301)
    {
        $response = $this->responseObject;
        $response->getBody()
                ->write('');
        $response = $response->withStatus($status)
                ->withHeader('Location', $url);
        return $response;
    }

}
