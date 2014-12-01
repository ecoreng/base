<?php

namespace Base;

use \Base\Interfaces\RouterInterface as Router;
use \Base\Interfaces\DispatcherInterface as Dispatcher;
use \Base\Interfaces\AutoloaderInterface as Autoloader;
use \Base\Interfaces\SessionInterface as Session;
use \Base\Interfaces\ViewInterface as View;
use \Base\Interfaces\ServerSideMessageFactoryInterface as MessageFactory;
use \Base\Interfaces\ResponseSenderInterface as ResponseSender;
use \Base\Interfaces\AppInterface as AppInterface;
use \Psr\Http\Message\IncomingRequestInterface as Request;

class App implements AppInterface
{

    protected $router;
    protected $dispatcher;
    protected $autoloader;
    protected $session;
    protected $config = [
        'environment' => [
            'base-url' => ''
        ],
        'app' => [
            'mode' => 'dev'
        ]
    ];
    protected $messageFactory;
    protected $responseSender;
    protected $request;

    public function __construct(
    Router $router, Dispatcher $dispatcher, Autoloader $autoloader, Session $session, $config, View $view,
            MessageFactory $messageFactory, ResponseSender $responseSender, Request $request
    )
    {
        $this->router = $router;
        $this->dispatcher = $dispatcher;
        $this->autoloader = $autoloader;                                    // not used yet      [should it?]
        $this->session = $session;                                          // not used yet      [should it?]
        $this->config = array_replace_recursive($this->config, $config);    // not used here yet
        $this->view = $view;                                                // not used here yet [and it shouldnt]
        $this->messageFactory = $messageFactory;
        $this->responseSender = $responseSender;
        $this->request = $request;
    }

    /**
     * Router
     */
    public function getRouter()
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
    protected function dispatch(Request $request = null, $sendResponse = true)
    {
        if ($request === null) {
            $request = $this->request;
        }
        $this->dispatcher->setBaseUrl($this->config['environment']['base-url']);
        $response = $this->dispatcher->dispatch($request);
        // for anonymous functions returning text is easier
        if (is_string($response)) {
            $responseObject = $this->messageFactory->newOutgoingResponse();
            $responseObject->content->set($response);
            $response = $responseObject;
        }

        if ($sendResponse) {
            $this->responseSender->setResponse($response);
            $this->responseSender->send();
        } else {
            return $response;
        }
    }

    /**
     * App
     */
    public function run()
    {
        $response = $this->dispatch();
    }

    public function subRequest($url, array $subEnvironment = [])
    {
        $environment = [
            '_ENV' => $_ENV,
            '_GET' => $_GET,
            '_POST' => $_POST,
            '_COOKIE' => $_COOKIE,
            '_SERVER' => $_SERVER
        ];
        $environment = array_merge_recursive($environment, $subEnviroment, ['_SERVER' => ['REQUEST_URI' => $url]]);
        $this->messageFactory->resetFactory($environment);
        $request = $this->messageFactory->newIncomingRequest();
        $response = $this->dispatch($request, false);
        $this->messageFactory->resetFactory();
        return $response;
    }

    public function setConfig($domain = 'app', $key, $value)
    {
        if (!array_key_exists($domain, $this->config)) {
            $this->config[$domain] = [];
        }
        $this->config[$domain][$key] = $value;
    }

    public function getConfig($domain, $key = null)
    {
        if ($key === null) {
            if (array_key_exists($domain, $this->config)) {
                return $this->config[$domain];
            }
        } else {
            if (array_key_exists($key, $this->config[$domain])) {
                return $this->config[$domain][$key];
            }
        }
        return null;
    }

}
