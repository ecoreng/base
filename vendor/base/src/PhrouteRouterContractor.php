<?php

namespace Base;

class PhrouteRouterContractor extends \Phroute\RouteCollector implements \Base\Interfaces\RouterInterface
{
    public function addRoute()
    {
        $args = func_get_args();
        return call_user_func_array('parent::addRoute', $args);
    }
}