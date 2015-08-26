<?php

namespace Base\Concrete;

use \Base\Router;
use \Base\Dispatcher;
use \Base\Autoloader;
use \Base\Interfaces\ServerSideMessageFactoryInterface as MessageFactory;
use \Base\ResponseSender;
use \Base\Config;
use \Base\App as AppInterface;
use \Base\ErrorHandler;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Interop\Container\ContainerInterface as IContainer;
use \Base\Middleware;
use \Base\Interfaces\MiddlewareCallableInterface as MiddlewareCallable;
use \Phly\Http\Stream;

class App implements AppInterface, MiddlewareCallable
{

    protected $router;
    protected $dispatcher;
    protected $autoloader;
    protected $config;
    protected $messageFactory;
    protected $responseSender;
    protected $request;
    protected $di;
    protected $middleware = [];
    protected $errorHandler;

    public function __construct(
        Router $router,
        Dispatcher $dispatcher,
        Autoloader $autoloader,
        Config $config,
        MessageFactory $messageFactory,
        ResponseSender $responseSender,
        Request $request,
        IContainer $di,
        ErrorHandler $errorHandler
    )
    {
        $this->router = $router;
        $this->dispatcher = $dispatcher;
        $this->autoloader = $autoloader;
        $this->config = $config;
        $this->messageFactory = $messageFactory;
        $this->responseSender = $responseSender;
        $this->request = $request;
        $this->di = $di;
        $this->errorHandler = $errorHandler;
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
        return $this->getRouter()->getRoute($name, $params);
    }

    /**
     * Dispatcher
     */
    protected function dispatch(Request $request = null)
    {
        if ($request === null) {
            $request = $this->request;
        }
        $this->dispatcher->setBaseUrl($this->getConfig('environment.base-url'));
        $response = $this->dispatcher->dispatch($request);
        // for anonymous functions returning strings might be easier
        if (is_string($response)) {
            $responseObject = $this->messageFactory->newResponse();
            $responseObject->getBody()->write($response);
            $response = $responseObject;
        }
        return $response;
    }

    /**
     * Run all registered middleware, and the app at the end
     */
    public function run($sendResponse = true, $bypassErrorHandler = false)
    {
        $lastMiddleware = end($this->middleware);
        if ($lastMiddleware instanceof Middleware) {
            if (!$lastMiddleware->getNextMiddleware()) {
                $lastMiddleware->setNextMiddleware($this);
            }
        }

        $firstMiddleware = reset($this->middleware);
        $firstMiddleware = $firstMiddleware ? $firstMiddleware : $this;
        try {
            $request = $this->getRequest();

            $response = $firstMiddleware->call(
                $request,
                $this->di->get('Psr\Http\Message\ResponseInterface')
            );

            if ($sendResponse) {
                $this->getResponseSender()->setResponse($response);
                $this->getResponseSender()->send();
            } else {
                return $response;
            }
        } catch (\Exception $e) {
            if ($bypassErrorHandler === false) {
                $this->errorHandler->handle($e, $request, $this);
            } else {
                throw $e;
            }
        }
    }

    /**
     * Experimental sub-request function
     * 
     * @param string $url
     * @param array $subEnvironment
     * @return Response
     */
    public function subRequest($url, array $subEnvironment = [], $bypassErrorHandler = false)
    {
        $environment = [
            'query' => $_GET,
            'body' => $_POST,
            'cookies' => $_COOKIE,
            'files' => $_FILES,
            'server' => array_merge($_SERVER, ['REQUEST_METHOD' => 'GET'])
        ];
        $environment = array_replace_recursive($environment, $subEnvironment, ['server' => ['REQUEST_URI' => $url]]);
        $this->messageFactory->resetFactory($environment);
        $this->request = $this->messageFactory->newRequest();
        $response = $this->run(false, $bypassErrorHandler);
        $this->messageFactory->resetFactory();
        return $response;
    }

    /**
     * Run the app @ the end of the queue
     */
    public function call(Request $request, Response $response)
    {
        $this->request = $request;
        $this->di->set('Psr\Http\Message\RequestInterface', $this->getRequest());
        $this->di->set('Psr\Http\Message\ResponseInterface', $response);
        return $this->dispatch($request);
    }

    /**
     * Add / Set configuration
     * 
     * @param string $key
     * @param string $value
     */
    public function setConfig($key, $value)
    {
        $this->config->set($key, $value);
    }

    /**
     * Return the configuration set as $key
     * 
     * @param string $key
     * @return mixed|null
     */
    public function getConfig($key = null)
    {
        return $this->config->get($key);
    }

    /**
     * Set the whole config array replacing previous values
     * 
     * @param array $config
     */
    public function setConfigArray(array $config)
    {
        $this->config->setArray($config);
    }

    /**
     * Register a middleware in the queue
     * 
     * @param \Base\Middleware|string $middleware
     */
    public function add($middleware)
    {
        $mw = is_string($middleware) ? $this->di->get($middleware) : $middleware;
        if (count($this->middleware) > 0) {
            $fmw = end($this->middleware);
            $fmw->setNextMiddleware($mw);
        }

        $this->middleware[] = $mw;
    }

    /**
     * Returns the router
     * 
     * @return \Base\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Returns the response sender
     * 
     * @return \Base\ResponseSender
     */
    public function getResponseSender()
    {
        return $this->responseSender;
    }

    /**
     * Returns the dispatcher
     * 
     * @return \Base\Dispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Returns the autoloader
     * 
     * @return \Base\Autoload
     */
    public function getAutoloader()
    {
        return $this->autoloader;
    }

    /**
     * Returns the Request
     * 
     * @return \Psr\Http\Message\RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

}
