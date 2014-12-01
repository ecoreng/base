<?php

// require and return composer autoloader
$autoloader = require('../vendor/autoload.php');
// autoload EXAMPLE classes
$autoloader->addPsr4('ExampleCo\\Example\\', 'Example/');

$app = (new \Base\InjectorBuilder)
        ->register(new \Base\DefaultServiceRegisterer(['environment' => ['base-url' => '/base/example/index.php']], $autoloader))
        ->register(new \ExampleCo\Example\CustomServiceRegisterer)
        ->getDi()
        ->make('\Base\Interfaces\AppInterface');


$woops = new \Whoops\Run;
if ($app->getConfig('app', 'mode') === 'dev') {
    $woops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $woops->pushHandler(function($e){
        echo 'Friendly error page and send an email to the developer';
    });
}
$woops->register();


// use as microframework
$app->addRoute('GET', 'test/{id}', function ($id, \Psr\Http\Message\IncomingRequestInterface $request) {
    return 'woo ' . $id . '  via:' . $request->getMethod();
});



// use a controller to handle route
//$app->addRoute('GET', 'test/{id}', '\ExampleCo\Example\ExampleController:getIndex');

// load a full controller into the router (route prefix, controller class)
//$app->getRouter()->controller('/', '\ExampleCo\Example\ExampleController');

// run app
$app->run();

