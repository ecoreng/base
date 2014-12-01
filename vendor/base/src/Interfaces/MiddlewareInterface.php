<?php

namespace Base\Interfaces;

interface MiddlewareInterface extends MiddlewareCallableInterface
{

    public function setApplication(AppInterface $app);

    public function getApplication();

    public function setInjector(\Auryn\Injector $di);

    public function getInjector();

    public function setNextMiddleware(MiddlewareCallableInterface $mw);

    public function getNextMiddleware();
}
