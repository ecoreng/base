<?php

namespace Base\Concrete;

use \Base\Router;
use \Phroute\RouteCollector as Collector;

class PhrouteRouterAdapter extends Collector implements Router
{

    public function addRoute($httpMethod, $route, $handler, array $filters = [])
    {
        $args = func_get_args();
        return call_user_func_array('parent::addRoute', $args);
    }

    public function getRoute($name, $params = [])
    {
        return parent::route($name, $params);
    }

    public function __call($name, $args)
    {
        return call_user_func_array('parent::addRoute', $args);
    }

}