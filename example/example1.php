<?php
// require and return composer autoloader
$autoloader = require('../vendor/autoload.php');

use \Base\Concrete\DefaultServiceRegisterer as Services;
use \Base\Concrete\Container;

$c = new Container;
$c->register(new Services($autoloader));
$app = $c->get('Base\App');

$app->setConfig('environment.base-url', '/projects/Base/_proto-base/example/example1.php');

// use as microframework
$app->addRoute('GET', 'test/{id}', function ($id) {
    return 'test: ' . $id;
});

// run app
$app->run();
