<?php

namespace Base\Concrete;

use \Base\Autoloader;
use \Composer\Autoload\ClassLoader as ClassLoader;
use \Base\ServiceRegisterer as Service;
use \Interop\Container\ContainerInterface;

class DefaultServiceRegisterer implements Service
{

    protected $config;
    protected $autoloader;

    public function __construct(ClassLoader $autoloader, $config = [])
    {
        $this->config = $config;
        $this->autoloader = $autoloader;
    }

    public function register(ContainerInterface $di)
    {

        // Framework interfaces to Concrete implementations
        $di->set('Base\Router', 'Base\Concrete\PhrouteRouterAdapter');
        $di->set('Base\Dispatcher', 'Base\Concrete\PhrouteDispatcherAdapter')
                ->withSetter('setResolver', ['resolver' => '@Phroute\HandlerResolverInterface']);

        $di->set('Base\Session', 'Base\Concrete\AuraSessionAdapter');

        $di->set('Base\Interfaces\ServerSideMessageFactoryInterface', 'Base\Concrete\PhlyMessageFactory');
        $di->set('Psr\Http\Message\RequestInterface', 'Phly\Http\Request');
        $di->set('Base\ResponseSender', 'Base\Concrete\PhlyResponseSender');

        $di->set('Base\View', 'Base\Concrete\GenericJsonView');

        $di->set('Base\Autoloader', 'Base\Concrete\ComposerAutoloaderAdapter');
        $di->set('Base\App', 'Base\Concrete\App');

        $di->set('Interop\Container\ContainerInterface', 'Base\Concrete\Container');

        $di->set('Phroute\HandlerResolverInterface', 'Base\Concrete\PhrouteResolver');
        $di->set('Base\Concrete\PhrouteResolver', null, ['di' => $di]);

        $di->set('Base\Request', 'Psr\Http\Message\RequestInterface');
        $di->set('Base\Response', 'Psr\Http\Message\ResponseInterface');

        // - Session Handling
        $di->set('Aura\Session\Session', function () {
            $sessionFactory = new \Aura\Session\SessionFactory;
            return $sessionFactory->newInstance($_COOKIE);
        });

        // - Request / Response Factory
        $wf = $di->get('Base\Interfaces\ServerSideMessageFactoryInterface');
        $req = $wf->newRequest();
        $di->set('Phly\Http\Request', function () use ($req) {
            return $req;
        });
        $di->set('Psr\Http\Message\ResponseInterface', 'Phly\Http\Response');
        $di->set('Phly\Http\Response', function() use ($wf) {
            return $wf->newResponse();
        });

        // - All controllers implementing the ControllerInterface
        $mf = $di->get('Base\Interfaces\ServerSideMessageFactoryInterface');
        $di->set('Base\Controller')
                ->withSetter('setView', ['view' => '@Base\View'])
                ->withSetter('setResponse', ['response' => '@Psr\Http\Message\ResponseInterface'])
                ->withSetter('setRequest', ['request' => '@Psr\Http\Message\RequestInterface']);

        // - Autoloader concretion that implements the AutoloaderInterface
        $di->set('Base\Concrete\ComposerAutoloaderAdapter', null, ['autoloader' => $this->autoloader]);

        // - Dependency Injection
        $di->set('Base\Concrete\Container', function () use ($di) {
            return $di;
        });

        $di->set('Base\Config', 'Base\Concrete\Config');
        $defaultConfig = [
            'environment.base-url' => '',
            'app.mode' => 'dev',
        ];
        $di->set('Base\Concrete\Config')->withArgument('data', array_merge($defaultConfig, $this->config));
        
        // aliases, not actual request / response objects or interfaces
        $di->set('Base\Request', 'Psr\Http\Message\RequestInterface');
        $di->set('Base\Response', 'Psr\Http\Message\ResponseInterface');
        
        // default error handler
        $di->set('Base\ErrorHandler', 'Base\Concrete\DefaultErrorHandler');
    }
}
