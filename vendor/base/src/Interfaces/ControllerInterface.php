<?php

namespace Base\Interfaces;

interface ControllerInterface
{

    public function setView(\Base\Interfaces\ViewInterface $view);

    public function setResponse(\Psr\Http\Message\OutgoingResponseInterface $response);

    public function setRequest(\Psr\Http\Message\IncomingRequestInterface $request);
}
