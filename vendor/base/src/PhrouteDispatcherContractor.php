<?php

namespace Base;

class PhrouteDispatcherContractor implements \Base\Interfaces\DispatcherInterface
{

    protected $router;
    protected $resolver;

    public function __construct(\Base\Interfaces\RouterInterface $router, \Phroute\HandlerResolverInterface $resolver)
    {
        $this->router = $router;
        $this->resolver = $resolver;
    }

    protected function initDispatcher()
    {
        $this->dispatcher = new \Phroute\Dispatcher($this->router, $this->resolver);
    }

    public function dispatch(\Base\Interfaces\RequestInterface $request)
    {
        $this->initDispatcher();

        // bad
        $remove = explode("/", $_SERVER['SCRIPT_NAME']);
        array_pop($remove);
        $remove = implode("/", $remove);
        $url = str_replace([$_SERVER['SERVER_NAME'], ':' . $_SERVER['SERVER_PORT'], $remove, $_SERVER['REQUEST_SCHEME'] . '://'], ['', '', '', ''], $request->getUrl());
        
        ob_start();
        $response = $this->dispatcher->dispatch($request->getMethod(), $url);
        $body = ob_get_clean();
        ob_end_clean();
        
        if ($response instanceof \Base\Interfaces\ResponseInterface) {
            return $response;
        } else {
            return $body;
        }
    }

}
