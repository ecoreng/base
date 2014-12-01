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

        if (is_string($handler)) {
            if (strpos($handler, ':') !== false) {
                $handler = explode(':', $handler);
            }
        }

        if (is_array($handler) and is_string($handler[0])) {
            $handler[0] = $this->di->make($handler[0]);
        }

        if ($handler instanceof \Closure) {
            $di = $this->di;
            $handler = function () use ($di, $handler) {
                $args = func_get_args();
                
                $params = (new \ReflectionFunction($handler))->getParameters();

                $urlArgs = array_intersect_key($params, $args);
                $urlArgsReady = [];
                foreach ($urlArgs as $key => $arg) {
                    $urlArgsReady[':' . $arg->name] = $args[$key];
                }
                return $di->execute($handler, $urlArgsReady);
            };
        }
        return $handler;
    }

}
