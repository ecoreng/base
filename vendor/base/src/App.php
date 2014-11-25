<?php

namespace Base;

class App
{

    protected $router;
    protected $environment;
    protected $dispatcher;
    protected $autoloader;
    protected $session;
    protected $config;
    protected $messageFactory;
    protected $responseSender;

    public function __construct(
    \Base\Interfaces\RouterInterface $router, \Base\Interfaces\DispatcherInterface $dispatcher, \Base\Interfaces\ConfigInterface $environment, \Composer\Autoload\ClassLoader $autoloader, \Base\Interfaces\SessionInterface $session, \Base\Interfaces\ConfigInterface $config, \Base\Interfaces\ViewInterface $view, \Base\Interfaces\ServerSideMessageFactoryInterface $messageFactory, \Base\Interfaces\ResponseSenderInterface $responseSender
    )
    {
        $this->router = $router;
        $this->dispatcher = $dispatcher;
        $this->environment = $environment;
        $this->autoloader = $autoloader;
        $this->session = $session;
        $this->config = $config;
        $this->view = $view;
        $this->messageFactory = $messageFactory;
        $this->responseSender = $responseSender;
    }

    /**
     * Router
     */
    public function router()
    {
        return $this->router;
    }

    public function addRoute()
    {
        $args = func_get_args();
        return call_user_func_array([$this->router, 'addRoute'], $args);
    }

    /**
     * Dispatcher
     */
    public function dispatch()
    {
        // 'GET', 'a'
        $response = $this->dispatcher->dispatch($this->messageFactory->newIncomingRequest());
        // Pampering our users (?)
        if (is_string($response)) {
            $responseObject = $this->messageFactory->newOutgoingResponse();
            $responseObject->content->set($response);
            $response = $responseObject;
        }

        $this->responseSender->setResponse($response);
        $this->responseSender->send();
    }

    /**
     * App
     */
    public function run()
    {
        $response = $this->dispatch();
    }

}
