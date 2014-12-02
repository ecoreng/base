<?php

namespace Base\Interfaces;

interface DispatcherInterface
{
    public function __construct(\Base\Interfaces\RouterInterface $router);
    public function setBaseUrl($baseUrl);
    public function dispatch(\Psr\Http\Message\IncomingRequestInterface $request);
}
