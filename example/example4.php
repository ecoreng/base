<?php

// require and return composer autoloader
$autoloader = require('../vendor/autoload.php');

// autoload EXAMPLE classes
$autoloader->addPsr4('ExampleCo\\Example\\', 'Example/');

// aliasing
use \Base\DefaultServiceRegisterer as DefaultServices;
use \Base\InjectorBuilder as Builder;
use \ExampleCo\Example\CustomServiceRegisterer as CustomServices;

/*
 * Get the app instance registered to fullfill the AppInterface Contract; from an injector built using
 * those service registerers
 */
$app = (new Builder)
        ->register(new DefaultServices($autoloader))
        ->register(new CustomServices)
        ->getDi()
        ->make('\Base\Interfaces\AppInterface');

// Optional Config base-url if necessary
$app->setConfig('environment.base-url', '/base/example/example4.php');

// Optional Custom Error Handler
$woops = new \Whoops\Run;
if ($app->getConfig('app.mode') === 'dev') {
    $woops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $woops->pushHandler(function ($e) {
        echo 'Friendly error page and send an email to the developer';
    });
}
$woops->register();

// load a full controller into the router (route prefix, controller class)
$app->getRouter()->controller('/', '\ExampleCo\Example\ExampleController');

// run app
$app->run();
