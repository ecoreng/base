<?php
define('DS', DIRECTORY_SEPARATOR);
$autoloader = require('../vendor/autoload.php');
$autoloader->addPsr4('ExampleCo\\Example\\', 'Example' . DS);

use \Base\App;
use \Aura\Session\SessionFactory;

$di = new Auryn\Provider;

// concrete instance dependencies
$di->define('\Base\App', [
    ':environment' => $di->make('\Base\Config', [":config" => $_SERVER]),
    ':autoloader' => $autoloader,
    ':config' => $di->make('\Base\Config', [":config" => []]),
]);

// Autoresolution of dependencies and interface aliasing
$di->alias('\Base\Interfaces\RouterInterface', '\Base\PhrouteRouterContractor');
$di->share('\Base\PhrouteRouterContractor');

$di->alias('\Base\Interfaces\DispatcherInterface', '\Base\PhrouteDispatcherContractor');
$di->share('\Base\PhrouteDispatcherContractor');
$di->alias('\Phroute\HandlerResolverInterface', '\Base\PhrouteResolver');
$di->share('\Base\PhrouteResolver');
$di->define('\Base\PhrouteResolver', [':di' => $di]);

$di->alias('\Base\Interfaces\SessionInterface', '\Base\AuraSessionContractor');
$di->share('\Base\AuraSessionContractor');
$sessionFactory = new \Aura\Session\SessionFactory;
$di->delegate('\Aura\Session\Session', function () use ($sessionFactory){
    return $sessionFactory->newInstance($_COOKIE);
});

$di->alias('\Base\Interfaces\ViewInterface', '\Base\PlatesView');
$di->share('\Base\PlatesView');

$di->alias('\Base\Interfaces\ServerSideMessageFactoryInterface', '\Base\AuraMessageFactoryContractor');
$di->share('\Base\AuraMessageFactoryContractor');

// create a webfactory factory from "environments" instead of this
// and delegate this class to that factory ?
$di->define('\Aura\Web\WebFactory', [':globals' => $GLOBALS]);

// create a way to define environments to pass to webfactory factory and get webfactories
// but keep a shared instance of a real request

// create an app api to deal with subrequests and use the webfactory factory to createa webfactory with the "virtual"
// environment of the request

$di->alias('\Base\Interfaces\RequestInterface', '\Base\AuraRequestContractor');
$di->share('\Base\AuraRequestContractor');
$wf = $di->make('\Base\Interfaces\ServerSideMessageFactoryInterface');
$di->delegate('\Base\AuraRequestContractor', function () use ($wf){
    return $wf->newIncomingRequest();
});

$di->alias('\Base\Interfaces\ResponseSenderInterface', '\Base\AuraResponseSenderContractor');

$di->prepare('\Base\Interfaces\ControllerInterface', function($obj, $di) {
    $obj->setView($di->make('\Base\Interfaces\ViewInterface'));
    $mf = $di->make('\Base\Interfaces\ServerSideMessageFactoryInterface');
    $obj->setResponse($mf->newOutgoingResponse());
});

$app = $di->make('\Base\App');


  //$di->set('RouteLoader');

  /*
  $defaultSettings = require('../app/settings.php');
  $modeSettings = require('../app/mode_settings.php');
  $config = array_merge_recursive($defaultSettings, $modeSettings);
  */
  
  // load a module


$app->addRoute('GET', '/', '\ExampleCo\Example\ExampleController:getDemo');

$app->run();
