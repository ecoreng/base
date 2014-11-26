<?php

// require and return composer autoloader
// -- create an interface for this and require that instead of the concretion
//    of composer autoloader
//      addPsr4 and add (psr-0)
$autoloader = require('../vendor/autoload.php');

// autoload EXAMPLE classes
$autoloader->addPsr4('ExampleCo\\Example\\', 'Example/');

// define all services and return an ready-to-use instance of the app
$app = \Base\DefaultApp::init(['environment' => ['base-url' => '/base/example/']], $autoloader);

// use framework as micro
$app->addRoute('GET', '', function () {
    return 'woo';
});

// use a controller to handle route
//$app->addRoute('GET', '/', '\ExampleCo\Example\ExampleController:getDemo');

// load a full controller into the router (route prefix, controller class)
//$app->router()->controller('/', '\ExampleCo\Example\ExampleController');


// run app
$app->run();
