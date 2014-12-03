<?php

namespace Base;

class DefaultServiceRegisterer implements \Base\Interfaces\ServiceRegistererInterface
{

    protected $di;
    protected $config;
    protected $autoloader;

    public function __construct(\Composer\Autoload\ClassLoader $autoloader, $config = [])
    {
        $this->config = $config;
        $this->autoloader = $autoloader;
    }

    public function register(\Auryn\Injector $di)
    {
        $this->di = $di;
        $this->setAliases();
        $this->setShared();
        $this->setImplementations();
    }

    protected function setAliases()
    {
        // Framework interfaces to Concrete implementations
        $this->di->alias('\Base\Interfaces\RouterInterface', '\Base\PhrouteRouterContractor');
        $this->di->alias('\Base\Interfaces\DispatcherInterface', '\Base\PhrouteDispatcherContractor');
        $this->di->alias('\Base\Interfaces\SessionInterface', '\Base\AuraSessionContractor');
        $this->di->alias('\Base\Interfaces\ServerSideMessageFactoryInterface', '\Base\AuraMessageFactoryContractor');
        $this->di->alias('\Psr\Http\Message\IncomingRequestInterface', '\Base\AuraRequestContractor');
        $this->di->alias('\Base\Interfaces\ResponseSenderInterface', '\Base\AuraResponseSenderContractor');
        $this->di->alias('\Base\Interfaces\ViewInterface', '\Base\GenericJsonView');
        $this->di->alias('\Base\Interfaces\AutoloaderInterface', '\Base\ComposerAutoloaderContractor');
        $this->di->alias('\Base\Interfaces\AppInterface', '\Base\App');
        $this->di->alias('\Auryn\Injector', '\Auryn\Provider');

        // Custom concrete implementation interfaces
        $this->di->alias('\Phroute\HandlerResolverInterface', '\Base\PhrouteResolver');
    }

    protected function setShared()
    {
        // Shared Concretions
        $this->di->share('\Base\PhrouteRouterContractor');
        $this->di->share('\Base\PhrouteDispatcherContractor');
        $this->di->share('\Base\PhrouteResolver');
        $this->di->share('\Base\AuraSessionContractor');
        $this->di->share('\Base\AuraMessageFactoryContractor');
        $this->di->share('\Base\AuraRequestContractor');
        $this->di->share('\Aura\Session\Session');
        $this->di->share('\Base\ComposerAutoloaderContractor');
        $this->di->share('\Auryn\Provider');

        //$this->di->share('\Base\PlatesView');
        $this->di->share('\Base\GenericJsonView');
    }

    protected function setImplementations()
    {
        // Concrete implementation initialization instructions
        // - Routing
        $di = $this->di;
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

        // - Autoloader concretion that implements the AutoloaderInterface
        $di->define('\Base\ComposerAutoloaderContractor', [':autoloader' => $this->autoloader]);

        // - Dependency Injection
        $di->delegate('\Auryn\Provider', function () use ($di) {
            return $di;
        });


        // - Framework default app
        $di->define('\Base\App', [
            ':config' => $this->config,
        ]);
    }

}
