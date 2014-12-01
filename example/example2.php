<?php

// require and return composer autoloader
$autoloader = require('../vendor/autoload.php');

// autoload EXAMPLE classes
$autoloader->addPsr4('ExampleCo\\Example\\', 'Example/');

use \Base\DefaultServiceRegisterer as DefaultServices;
use \Base\InjectorBuilder as Builder;
use \ExampleCo\Example\CustomServiceRegisterer as CustomServices;
use \Psr\Http\Message\IncomingRequestInterface as Request;
use \Base\Interfaces\ServerSideMessageFactoryInterface as MessageFactory;

$app = (new Builder)
        ->register(new DefaultServices($autoloader))
        ->register(new CustomServices)
        ->getDi()
        ->make('\Base\Interfaces\AppInterface');

$app->setConfig('environment.base-url', '/base/example/example2.php');

$woops = new \Whoops\Run;
if ($app->getConfig('app.mode') === 'dev') {
    $woops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $woops->pushHandler(function ($e) {
        echo 'Friendly error page and send an email to the developer';
    });
}
$woops->register();

// use a controller to handle route using a custom method in the overriden app (see CustomServiceRegisterer)
$app->get('test', '\ExampleCo\Example\ExampleController:getIndex');

$app->add(new \ExampleCo\Example\CustomMiddleware);

// run app
$app->run();

