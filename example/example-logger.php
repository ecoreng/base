<?php

// require and return composer autoloader
$autoloader = require('../vendor/autoload.php');

use \Base\Concrete\DefaultServiceRegisterer as Services;
use \Base\Concrete\Container;
use \Psr\Log\LoggerInterface as Logger;

$c = new Container;
$c->register(new Services($autoloader));
$app = $c->get('Base\App');

// set the base url for this script (optional)
$app->setConfig('environment.base-url', '/example-logger.php');

// use as microframework with Dependency Injection AND route params
$app->addRoute('GET', 'test/{id:i}', function ($id, Logger $logger) {
    $logger->debug('All good', ['test/' . $id]);
    return 'All good: ' . $id;
});

$app->run();
