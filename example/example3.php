<?php

// require and return composer autoloader
$autoloader = require('../vendor/autoload.php');

use \Base\DefaultServiceRegisterer as DefaultServices;
use \Base\InjectorBuilder as Builder;
use \Psr\Http\Message\IncomingRequestInterface as Request;

$app = (new Builder)
        ->register(new DefaultServices($autoloader))
        ->getDi()
        ->make('\Base\App');

$app->setConfig('environment.base-url', '/base/example/example3.php');

// use as microframework with Dependency Injection AND route params
$app->addRoute('GET', 'test/{id:i}', function ($id, Request $request) {
    return 'test: ' . $id . '; via:' . $request->getMethod();
});

$app->run();
