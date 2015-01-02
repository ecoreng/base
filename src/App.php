<?php

namespace Base;

use \Base\Interfaces\RouterInterface as Router;
use \Base\Interfaces\DispatcherInterface as Dispatcher;
use \Base\Interfaces\AutoloaderInterface as Autoloader;
use \Base\Interfaces\ServerSideMessageFactoryInterface as MessageFactory;
use \Base\Interfaces\ResponseSenderInterface as ResponseSender;
use \Base\Interfaces\AppInterface as AppInterface;
use \Psr\Http\Message\IncomingRequestInterface as Request;
use \Psr\Http\Message\OutgoingResponseInterface as Response;
use \Auryn\Injector;
use \Base\Interfaces\MiddlewareInterface as Middleware;
use \Base\Interfaces\MiddlewareCallableInterface as MiddlewareCallable;

class App implements AppInterface, MiddlewareCallable
{

    protected $router;
    protected $dispatcher;
    protected $autoloader;
    protected $config = [
        'environment.base-url' => '',
        'app.mode' => 'dev',
    ];
    protected $messageFactory;
    protected $responseSender;
    protected $request;
    protected $di;
    protected $middleware = [];

    public function __construct(
        Router $router,
        Dispatcher $dispatcher,
        Autoloader $autoloader,
        $config = [],
        MessageFactory $messageFactory,
        ResponseSender $responseSender,
        Request $request,
        Injector $di
    )
    {
        $this->router = $router;
        $this->dispatcher = $dispatcher;
        $this->autoloader = $autoloader;
        $this->config = array_replace_recursive($this->config, $config);
        $this->messageFactory = $messageFactory;
        $this->responseSender = $responseSender;
        $this->request = $request;
        $this->di = $di;

    }

    /**
     * Router
     */
    
    /**
     * Get the router
     * 
     * @return \Base\Interfaces\RouterInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Add a route
     * 
     * @return \Base\Interfaces\RouterInterface
     */
    public function addRoute()
    {
        $args = func_get_args();
        return call_user_func_array([$this->router, 'addRoute'], $args);
    }

    /**
     * Get route url from route name
     * 
     * @param string $name
     * @param array $params
     * @return string
     */
    public function getRoute($name, $params = [])
    {
        return $this->getRouter()->route($name, $params);
    }

    /**
     * Dispatcher
     */
    protected function dispatch(Request $request = null, $sendResponse = true)
    {
        if ($request === null) {
            $request = $this->request;
        }
        $this->dispatcher->setBaseUrl($this->getConfig('environment.base-url'));
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
    
    /**
     * Run all registered middleware, and the app at the end
     */
    public function run()
    {
        $lastMiddleware = end($this->middleware);
        if ($lastMiddleware instanceof Middleware) {
            $lastMiddleware->setNextMiddleware($this);
        }
        $this->middleware[] = $this;
        $firstMiddleware = reset($this->middleware);
        $firstMiddleware->call();
    }

    
    /**
     * Run the app @ the end
     */
    public function call()
    {
        $response = $this->dispatch();
    }

    
    /**
     * Run a sunrequest through the app but return the response
     * without modifying the state of the app (experimental)
     * 
     * @param string $url
     * @param array $subEnvironment
     * @return \Psr\Http\Message\OutgoingResponseInterface
     */
    public function subRequest($url, array $subEnvironment = [])
    {
        $environment = [
            '_ENV' => $_ENV,
            '_GET' => $_GET,
            '_POST' => $_POST,
            '_COOKIE' => $_COOKIE,
            '_SERVER' => array_merge($_SERVER, ['REQUEST_METHOD' => 'GET'])
        ];
        $environment = array_merge_recursive($environment, $subEnvironment, ['_SERVER' => ['REQUEST_URI' => $url]]);
        $this->messageFactory->resetFactory($environment);
        $request = $this->messageFactory->newIncomingRequest();
        $response = $this->dispatch($request, false);
        $this->messageFactory->resetFactory();
        return $response;
    }

    /**
     * App Config
     */
    
    /**
     * Add / Set configuration
     * 
     * @param string $key
     * @param string $value
     */
    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * Return the configuration set as $key
     * 
     * @param string $key
     * @return mixed|null
     */
    public function getConfig($key = null)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }
        return null;
    }

    /**
     * Set the whole config array replacing previous values
     * 
     * @param array $config
     */
    public function setConfigArray(array $config)
    {
        $this->config = array_replace_recursive($this->config, $config);
    }

    /**
     * Middleware 
     */
    
    /**
     * Register a middleware in the queue
     * 
     * @param \Base\Interfaces\MiddlewareInterface $middleware
     */
    public function add(Middleware $middleware)
    {
        $middleware->setApplication($this);
        $middleware->setInjector($this->di);

        if (count($this->middleware) > 0) {
            $middleware->setNextMiddleware(end($this->middleware));
        }
        $this->middleware[] = $middleware;
    }
}
