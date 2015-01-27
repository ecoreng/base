<?php

// require and return composer autoloader
$autoloader = require('../vendor/autoload.php');

// autoload EXAMPLE classes
$autoloader->addPsr4('ExampleCo\\Example\\', 'Example/');

use \Base\Concrete\DefaultServiceRegisterer as Services;
use \Base\Concrete\Container;
use \ExampleCo\Example\CustomServiceRegisterer as CustomServices;

$c = new Container;
$c->register(new Services($autoloader));
$c->register(new CustomServices);
$app = $c->get('Base\App');

$app->setConfig('environment.base-url', '/Projects/base-repo/example/example2.php');

// use a controller to handle route using a custom method in the overriden app (see CustomServiceRegisterer)
$app->get('test', '\ExampleCo\Example\ExampleController:getIndex');

$app->add(new \ExampleCo\Example\CustomMiddleware);

// run app
$app->run();

