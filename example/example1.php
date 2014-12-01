<?php

// require and return composer autoloader
$autoloader = require('../vendor/autoload.php');

use \Base\DefaultServiceRegisterer as DefaultServices;
use \Base\InjectorBuilder as Builder;

$app = (new Builder)
        ->register(new DefaultServices($autoloader))
        ->getDi()
        ->make('\Base\App');

$app->setConfig('environment.base-url', '/base/example/example1.php');

// use as microframework
$app->addRoute('GET', 'test/{id}', function ($id) {
    return 'test: ' . $id;
});

// run app
$app->run();
