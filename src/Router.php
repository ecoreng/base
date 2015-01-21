<?php

namespace Base;

interface Router
{
    public function addRoute($httpMethod, $route, $handler, array $filters = []);
    public function getRoute($name, $params = []);
}
