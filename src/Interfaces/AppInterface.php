<?php

namespace Base\Interfaces;

use \Psr\Http\Message\IncomingRequestInterface as Request;

interface AppInterface
{
    public function getRouter();
    public function addRoute();
    public function getRoute($name, $params = []);
    public function setConfig($key, $value);
    public function getConfig($key = null);
    public function setConfigArray(array $config);
    public function run();
}
