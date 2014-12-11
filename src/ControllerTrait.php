<?php

namespace Base;

trait ControllerTrait
{

    protected $view;
    protected $response;
    protected $request;

    public function setView(\Base\Interfaces\ViewInterface $view)
    {
        $this->view = $view;
    }

    public function setResponse(\Psr\Http\Message\OutgoingResponseInterface $response)
    {
        $this->response = $response;
    }

    public function setRequest(\Psr\Http\Message\IncomingRequestInterface $request)
    {
        $this->request = $request;
    }

    protected function render($template, $data = [], $status = 200, $contentType = 'text/html; charset=utf-8')
    {
        $view = $this->getView();
        $prefix = isset($this->viewPrefix) ? $this->viewPrefix : null;
        return $this->response($view->render($template, $data, $prefix), $status, $contentType);
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
        return $this->getViewObject() instanceof \Base\Interfaces\ViewInterface;
    }

    protected function response($body = '', $status = 200, $contentType = 'text/html; charset=utf-8')
    {
        $response = $this->response;
        //Temporary fix (read about this @ its definition in AuraResponseContractor)
        $response->setBodyString($body);
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
        //Temporary fix (read about this @ its definition in AuraResponseContractor)
        $response->setBodyString('');
        $response->setStatus($status);
        $response->setHeader('Location', $url);
        return $response;
    }

}
