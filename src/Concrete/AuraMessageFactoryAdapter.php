<?php

namespace Base\Concrete;

use \Base\Interfaces\ServerSideMessageFactoryInterface as MessageFactory;
use \Base\Concrete\AuraRequestAdapter as Request;
use \Base\Concrete\AuraResponseAdapter as Response;
use \Aura\Web\WebFactory;

class AuraMessageFactoryAdapter implements MessageFactory
{

    protected $factory;

    public function __construct(array $environment = [])
    {
        $this->resetFactory($environment);
    }

    public function setFactory(WebFactory $factory)
    {
        $this->factory = $factory;
    }

    public function resetFactory(array $environment = [])
    {
        $env = array_key_exists('_ENV', $environment) ? array_merge($_ENV, $environment['_ENV']) : $_ENV;
        $get = array_key_exists('_GET', $environment) ? array_merge($_GET, $environment['_GET']) : $_GET;
        $post = array_key_exists('_POST', $environment) ? array_merge($_POST, $environment['_POST']) : $_POST;
        $cookie = array_key_exists('_COOKIE', $environment) ? array_merge($_COOKIE, $environment['_COOKIE']) : $_COOKIE;
        $server = array_key_exists('_SERVER', $environment) ? array_merge($_SERVER, $environment['_SERVER']) : $_SERVER;

        $this->factory = new WebFactory([
            '_ENV' => $env,
            '_GET' => $get,
            '_POST' => $post,
            '_COOKIE' => $cookie,
            '_SERVER' => $server
        ]);
    }

    public function newIncomingRequest()
    {
        return new Request($this->factory->newRequest());
    }

    public function newOutgoingResponse()
    {
        return new Response($this->factory->newResponse());
    }
}
