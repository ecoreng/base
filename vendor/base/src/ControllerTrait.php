<?php

namespace Base;

trait ControllerTrait
{

    protected $view;
    protected $response;
    
    public function setView(\Base\Interfaces\ViewInterface $view)
    {
        $this->view = $view;
    }

    public function setResponse(\Base\Interfaces\ResponseInterface $response)
    {
        $this->response = $response;
    }

    protected function render($template, $data = [], $status = 200, $contentType = 'text/html; charset=utf-8')
    {
        $view = $this->getView();
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
        return $this->getView() instanceof \Base\Interfaces\ViewInterface;
    }

    protected function response($body = '', $status = 200, $contentType = 'text/html; charset=utf-8')
    {
        $response = $this->response;
        $response->setBody($body);
        $response->setStatus($status);
        $response->setHeader('Content-type', $contentType);
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
        $response = $this->response;
        $response->setBody('');
        $response->setStatus($status);
        $response->setHeader('Location', $url);
        return $response;
    }
}
