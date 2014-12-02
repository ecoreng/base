<?php

namespace Base\Interfaces;

interface RouterInterface
{
    public function addRoute();
    public function getRoute($name, $params = []);
}
