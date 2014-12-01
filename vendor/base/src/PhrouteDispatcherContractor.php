<?php

namespace Base;

class PhrouteDispatcherContractor implements \Base\Interfaces\DispatcherInterface
{

    protected $router;
    protected $resolver;
    protected $baseUrl = '';

    public function __construct(\Base\Interfaces\RouterInterface $router)
    {
        $this->router = $router;
    }

    public function setResolver(\Phroute\HandlerResolverInterface $resolver)
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

    public function dispatch(\Psr\Http\Message\IncomingRequestInterface $request)
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
        ob_end_clean();

        if ($response instanceof \Base\Interfaces\ResponseInterface || $bufferedBody === '') {
            return $response;
        } else {
            return $bufferedBody;
        }
    }

}
