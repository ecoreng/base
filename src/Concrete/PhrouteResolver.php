<?php

namespace Base\Concrete;

use \Phroute\HandlerResolverInterface as Resolver;
use \Interop\Container\ContainerInterface as IContainer;

class PhrouteResolver implements Resolver
{

    protected $di;

    public function __construct(IContainer $di)
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
        $key = is_object($handler) ? spl_object_hash($handler) : $handler;

        // Create callable array from string
        if (is_string($handler)) {
            if (strpos($handler, ':') !== false) {
                $handler = explode(':', $handler);
            }
        }


        // Instantiate class from first element
        if (is_array($handler) && is_string(reset($handler))) {
            $handler[0] = $this->di->get(reset($handler));
        }

        $di = $this->di;

        // prepare object
        if (is_array($handler)) {
            if ($handler[0] instanceof \Base\Controller) {
                $di->setterInjectAs('Base\Controller', $handler[0]);
            }
        }

        // Dependency Injection for method, functions and closures
        $handler = function () use ($di, $key, $handler) {
            $exe = $di->getExecutableFromCallable($key, $handler, func_get_args());
            return $exe();
        };
        return $handler;
    }

}
