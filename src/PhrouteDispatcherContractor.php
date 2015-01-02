<?php

namespace Base;

use \Base\Interfaces\RouterInterface as Router;
use \Phroute\HandlerResolverInterface as Handler;
use \Psr\Http\Message\IncomingRequestInterface as Request;
use \Psr\Http\Message\OutgoingResponseInterface as Response;

class PhrouteDispatcherContractor implements \Base\Interfaces\DispatcherInterface
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
        $url = parse_url($request->getUrl(), PHP_URL_PATH);
        if ($this->baseUrl !== '') {
            if (stripos($url, $this->baseUrl) === 0) {
                $url = str_replace($this->baseUrl, '', $url);
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
