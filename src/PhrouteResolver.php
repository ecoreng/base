<?php

namespace Base;

use \Phroute\HandlerResolverInterface as Resolver;

class PhrouteResolver implements Resolver
{

    protected $di;

    public function __construct(\Auryn\Provider $di)
    {
        $this->di = $di;
    }

    /**
     * Create an instance of the given handler resolving its dependencies.
     *
     * @param $handler
     * @return array
     */
    public function resolve($handler)
    {

        // Create callable array from string
        if (is_string($handler)) {
            if (strpos($handler, ':') !== false) {
                $handler = explode(':', $handler);
            }
        }

        // Instantiate class from first element
        if (is_array($handler) && is_string(reset($handler))) {
            $handler[0] = $this->di->make(reset($handler));
        }

        $di = $this->di;

        // Dependency Injection for method, functions and closures
        $handler = function () use ($di, $handler) {
            $args = func_get_args();
            if (is_array($handler)) {
                // resolve the controller parameters
                $params = (new \ReflectionMethod($handler[0], $handler[1]))->getParameters();
            } else {
                // resolve the closure / function parameters
                $params = (new \ReflectionFunction($handler))->getParameters();
            }

            $urlArgs = array_intersect_key($params, $args);
            $urlArgsReady = [];
            foreach ($urlArgs as $key => $arg) {
                $urlArgsReady[':' . $arg->name] = $args[$key];
            }
            return $di->execute($handler, $urlArgsReady);
        };

        return $handler;
    }
}
