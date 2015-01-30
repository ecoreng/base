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
        $route = parent::route($name, $params);
        $pre = '/';
        if (substr($route, 0, 1) === '/') {
            $pre = '';
        }
        return $pre . $route;
    }

    public function __call($name, $args)
    {
        return call_user_func_array('parent::addRoute', $args);
    }

}
