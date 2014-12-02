<?php

namespace Base;

class PhrouteRouterContractor extends \Phroute\RouteCollector implements \Base\Interfaces\RouterInterface
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
