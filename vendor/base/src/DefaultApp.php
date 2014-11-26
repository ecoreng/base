<?php

namespace Base;

class DefaultApp
{

    protected $router;
    protected $dispatcher;
    protected $autoloader;
    protected $session;
    protected $config = [
        'environment' => [
            'base-url' => ''
        ]
    ];
    protected $messageFactory;
    protected $responseSender;
    protected $request;

    public function __construct(
        \Base\Interfaces\RouterInterface $router,
        \Base\Interfaces\DispatcherInterface $dispatcher,
        \Composer\Autoload\ClassLoader $autoloader,
        \Base\Interfaces\SessionInterface $session,
        $config,
        \Base\Interfaces\ViewInterface $view,
        \Base\Interfaces\ServerSideMessageFactoryInterface $messageFactory,
        \Base\Interfaces\ResponseSenderInterface $responseSender,
        \Base\Interfaces\RequestInterface $request
    )
    {
        $this->router = $router;
        $this->dispatcher = $dispatcher;
        $this->autoloader = $autoloader;
        $this->session = $session;
        $this->config = array_replace_recursive($this->config, $config);
        $this->view = $view;
        $this->messageFactory = $messageFactory;
        $this->responseSender = $responseSender;
        $this->request = $request;
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
    public function dispatch(\Base\Interfaces\RequestInterface $request = null, $sendResponse = true)
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

    public static function init(array $config = [], \Composer\Autoload\ClassLoader $autoloader, array $services = [])
    {

        if (count($services) > 0) {
            $di = (new \Auryn\InjectorBuilder)->fromArray($services);
            // create a new class using the injector builder methods
            // but add preparations w/ class handler or namespaced function
            // ---- find a way to use service preparation & delegation classes
            // ---- try to setup universal params somehow
        } else {
            $di = new \Auryn\Provider;
            
            // Framework default app
            $di->define('\Base\DefaultApp', [
                ':autoloader' => $autoloader,
                ':config' => $config,
            ]);
            
            // Framework interfaces to Concrete implementations
            $di->alias('\Base\Interfaces\RouterInterface', '\Base\PhrouteRouterContractor');
            $di->alias('\Base\Interfaces\DispatcherInterface', '\Base\PhrouteDispatcherContractor');
            $di->alias('\Base\Interfaces\SessionInterface', '\Base\AuraSessionContractor');
            $di->alias('\Base\Interfaces\ServerSideMessageFactoryInterface', '\Base\AuraMessageFactoryContractor');
            $di->alias('\Base\Interfaces\RequestInterface', '\Base\AuraRequestContractor');
            $di->alias('\Base\Interfaces\ResponseSenderInterface', '\Base\AuraResponseSenderContractor');
            $di->alias('\Base\Interfaces\ViewInterface', '\Base\PlatesView');
            
            // Custom concrete implementation interfaces
            $di->alias('\Phroute\HandlerResolverInterface', '\Base\PhrouteResolver');

            // Shared Concretions
            $di->share('\Base\PhrouteRouterContractor');
            $di->share('\Base\PhrouteDispatcherContractor');
            $di->share('\Base\PhrouteResolver');
            $di->share('\Base\AuraSessionContractor');
            $di->share('\Base\AuraMessageFactoryContractor');
            $di->share('\Base\AuraRequestContractor');
            $di->share('\Base\PlatesView');

            // Concrete implementation initialization instructions
            // - Routing
            $di->prepare('\Base\PhrouteDispatcherContractor', function ($obj, $di) {
                $obj->setResolver($di->make('\Phroute\HandlerResolverInterface'));
            });
            $di->define('\Base\PhrouteResolver', [':di' => $di]);

            // - Session Handling
            $di->delegate('\Aura\Session\Session', function () {
                $sessionFactory = new \Aura\Session\SessionFactory;
                return $sessionFactory->newInstance($_COOKIE);
            });

            // - Request / Response Factory
            $wf = $di->make('\Base\AuraMessageFactoryContractor');
            $req = $wf->newIncomingRequest();
            $di->delegate('\Base\AuraRequestContractor', function () use ($req) {
                return $req;
            });

            // - All controllers implementing the ControllerInterface
            $di->prepare('\Base\Interfaces\ControllerInterface', function($obj, $di) use ($req) {
                $obj->setView($di->make('\Base\Interfaces\ViewInterface'));
                $mf = $di->make('\Base\Interfaces\ServerSideMessageFactoryInterface');
                $obj->setResponse($mf->newOutgoingResponse());
                $obj->setRequest($req);
            });
        }
        
        return $di->make('\Base\DefaultApp');
    }

}
