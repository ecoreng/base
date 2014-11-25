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
$di->alias('\Base\Interfaces\RouterInterface', '\Base\PhrouteRouterProxy');
$di->share('\Base\PhrouteRouterProxy');

$di->alias('\Base\Interfaces\DispatcherInterface', '\Base\PhrouteDispatcherProxy');
$di->share('\Base\PhrouteDispatcherProxy');
$di->alias('\Phroute\HandlerResolverInterface', '\Base\PhrouteResolver');
$di->share('\Base\PhrouteResolver');
$di->define('\Base\PhrouteResolver', [':di' => $di]);

$di->alias('\Base\Interfaces\SessionInterface', '\Base\AuraSessionProxy');
$di->share('\Base\AuraSessionProxy');
$sessionFactory = new \Aura\Session\SessionFactory;
$di->delegate('\Aura\Session\Session', function () use ($sessionFactory){
    return $sessionFactory->newInstance($_COOKIE);
});

$di->alias('\Base\Interfaces\ViewInterface', '\Base\PlatesView');
$di->share('\Base\PlatesView');

$di->alias('\Base\Interfaces\ServerSideMessageFactoryInterface', '\Base\AuraMessageFactory');
$di->share('\Base\AuraMessageFactory');
$di->define('\Aura\Web\WebFactory', [':globals' => $GLOBALS]);

$di->alias('\Base\Interfaces\ResponseSenderInterface', '\Base\AuraResponseSender');

$di->prepare('\Base\Interfaces\ControllerInterface', function($obj, $di) {
    $obj->setView($di->make('\Base\Interfaces\ViewInterface'));
    $mf = $di->make('\Base\AuraMessageFactory');
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
