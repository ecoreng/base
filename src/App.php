<?php

namespace Base;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

interface App
{

    public function addRoute();

    public function getRoute($name, $params = []);

    public function setConfig($key, $value);

    public function getConfig($key = null);

    public function setConfigArray(array $config);

    public function run($sendResponse);

    public function call(Request $request, Response $response);

    public function add($middleware);
}
