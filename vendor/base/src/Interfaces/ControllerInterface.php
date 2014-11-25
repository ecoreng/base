<?php

namespace Base\Interfaces;

interface ControllerInterface
{

    public function setView(\Base\Interfaces\ViewInterface $view);

    public function setResponse(\Base\Interfaces\ResponseInterface $response);
}
