<?php

// require and return composer autoloader
$autoloader = require('../vendor/autoload.php');

use \Psr\Http\Message\RequestInterface as Request;
use \Base\Concrete\DefaultServiceRegisterer as Services;
use \Base\Concrete\Container;

$c = new Container;
$c->register(new Services($autoloader));
$app = $c->get('Base\App');

$app->setConfig('environment.base-url', '/Projects/base-repo/example/example3.php');

// use as microframework with Dependency Injection AND route params
$app->addRoute('GET', 'test/{id:i}', function ($id, Request $request) {
    return 'test: ' . $id . '; via:' . $request->getMethod();
});

$app->run();
