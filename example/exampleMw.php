<?php

// require and return composer autoloader
$autoloader = require('../vendor/autoload.php');

// autoload EXAMPLE classes
$autoloader->addPsr4('ExampleCo\\Example\\', 'Example/');

// aliasing
use \Base\Concrete\DefaultServiceRegisterer as Services;
use \Base\Concrete\Container;
/*
 * Get the object registered to fullfill the App interface contract; from an injector built using
 * those service registerers
 */
$c = new Container;
$c->register(new Services($autoloader));
$app = $c->get('Base\App');

// Optional Config base-url if necessary
$app->setConfig('environment.base-url', '/Projects/base-repo/example/exampleMw.php');

$app->add('ExampleCo\\Example\\CustomMiddleware');

// load a full controller into the router (route prefix, controller class)
$app->getRouter()->controller('/', '\ExampleCo\Example\ExampleController');

// run app
$app->run();
