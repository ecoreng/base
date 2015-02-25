<?php

// require and return composer autoloader
$autoloader = require('../vendor/autoload.php');

use \Base\Concrete\DefaultServiceRegisterer as Services;
use \Base\Concrete\Container;
use \Base\EventEmitter as Emitter;

$c = new Container;
$c->register(new Services($autoloader));
$app = $c->get('Base\App');

// set the base url for this script (optional)
$app->setConfig('environment.base-url', '/example-event.php');

$emitter = $c->get('Base\EventEmitter');
$emitter->addListener('some.event', function ($e, $p1, $p2) {
    echo 'Hello from the event. Passed arguments are: ' . $p1 . ', ' . $p2;
});

// use as microframework with Dependency Injection AND route params
$app->addRoute('GET', 'test/{id:i}', function ($id, Emitter $emitter) {
    $e = $emitter->emit('some.event', 'foo', 'bar');
    // HACK: dont return anything, so the response uses the echoed content from the buffer as the body
});

$app->run();
