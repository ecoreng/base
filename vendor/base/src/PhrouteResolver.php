<?php

namespace Base;

class PhrouteResolver implements \Phroute\HandlerResolverInterface
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
        if (is_string($handler)){
            if (strpos($handler, ':') !== false){
                $handler = explode(':', $handler);
            }
        }
        if (is_array($handler) and is_string($handler[0])) {
            $handler[0] = $this->di->make($handler[0]);
        }
        return $handler;
    }
}
