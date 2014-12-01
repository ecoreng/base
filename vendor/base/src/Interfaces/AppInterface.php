<?php

namespace Base\Interfaces;

use \Psr\Http\Message\IncomingRequestInterface as Request;

interface AppInterface
{
    public function getRouter();
    public function addRoute();
    public function run();
}
