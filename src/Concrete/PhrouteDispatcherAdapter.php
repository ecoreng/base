<?php

namespace Base\Concrete;

use \Base\Router;
use \Phroute\HandlerResolverInterface as Handler;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Base\Dispatcher;

class PhrouteDispatcherAdapter implements Dispatcher
{

    protected $router;
    protected $resolver;
    protected $baseUrl = '';

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function setRouter(Router $router) {
        $this->router = $router;
    }

    public function setResolver(Handler $resolver)
    {
        $this->resolver = $resolver;
    }

    protected function initDispatcher()
    {
        $this->dispatcher = new \Phroute\Dispatcher($this->router, $this->resolver);
    }

    public function setBaseUrl($baseUrl = '')
    {
        $this->baseUrl = $baseUrl;
    }

    public function dispatch(Request $request)
    {
        $this->initDispatcher();
        
        $url = $request->getUri()->getPath();
        if ($this->baseUrl !== '') {
            if (stripos($url, $this->baseUrl) === 0) {
                $url = substr($url, strlen($this->baseUrl) - 1);
            }
        }
        ob_start();
        $response = $this->dispatcher->dispatch($request->getMethod(), $url);
        $bufferedBody = ob_get_clean();

        if ($response instanceof Response || $bufferedBody === '') {
            return $response;
        } else {
            return $bufferedBody;
        }
    }
}
