<?php

// require and return composer autoloader
$autoloader = require('../vendor/autoload.php');

// autoload EXAMPLE classes
$autoloader->addPsr4('ExampleCo\\Example\\', 'Example/');

// aliasing
use \Base\Concrete\DefaultServiceRegisterer as Services;
use \Base\Concrete\Container;
use \ExampleCo\Example\CustomServiceRegisterer as CustomServices;


/*
 * Get the object registered to fullfill the App interface contract; from an injector built using
 * those service registerers
 */
$c = new Container;
$c->register(new Services($autoloader));
$c->register(new CustomServices);
$app = $c->get('Base\App');

// Optional Config base-url if necessary
$app->setConfig('environment.base-url', '/projects/Base/_proto-base/example/example4.php');

// load a full controller into the router (route prefix, controller class)
$app->getRouter()->controller('/', '\ExampleCo\Example\ExampleController');

// run app
$app->run();
