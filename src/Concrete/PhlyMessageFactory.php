<?php

namespace Base\Concrete;

use \Base\Interfaces\ServerSideMessageFactoryInterface as Factory;

class PhlyMessageFactory implements Factory
{

    protected $reqFactory = '\Phly\Http\ServerRequestFactory';
    protected $env = [];

    public function __construct($reqFactory = null, $env = [])
    {
        $this->reqFactory = $reqFactory ? $reqFactory : '\Phly\Http\ServerRequestFactory';
        $this->env = $env;
    }

    public function newRequest()
    {
        if (count($this->env) === 0) {
            return call_user_func([$this->reqFactory, 'fromGlobals']);
        }
        return call_user_func_array(
            $this->reqFactory . '::fromGlobals',
            [
                'server' => isset($this->env['server']) ? $this->env['server'] : null,
                'query' => isset($this->env['query']) ? $this->env['query'] : null,
                'body' => isset($this->env['body']) ? $this->env['body'] : null,
                'cookies' => isset($this->env['cookies']) ? $this->env['cookies'] : null,
                'files' => isset($this->env['files']) ? $this->env['files'] : null,
            ]
        );
    }

    public function newResponse()
    {
        return new \Phly\Http\Response();
    }

    public function resetFactory(array $environment = [])
    {
        $this->env = $environment;
    }

}
