<?php

namespace Base\Interfaces;

interface RouterInterface
{
    public function addRoute($httpMethod, $route, $handler, array $filters = []);
    public function getRoute($name, $params = []);
}
