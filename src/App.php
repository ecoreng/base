<?php

namespace Base;

use \Base\Middleware;

interface App
{

    public function getRequest();

    public function getDispatcher();

    public function getRouter();

    public function addRoute();

    public function getRoute($name, $params = []);

    public function setConfig($key, $value);

    public function getConfig($key = null);

    public function setConfigArray(array $config);

    public function run();

    public function call();

    public function add(Middleware $middleware);

    public function getAutoloader();
}
