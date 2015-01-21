<?php

namespace Base;

use \Base\Interfaces\MiddlewareCallableInterface as MiddlewareCallable;
use \Base\App;
use \Interop\Container\ContainerInterface as Container;

interface Middleware extends MiddlewareCallable
{

    public function setApplication(App $app);

    public function getApplication();

    public function setInjector(Container $di);

    public function getInjector();

    public function setNextMiddleware(MiddlewareCallable $mw);

    public function getNextMiddleware();
}
