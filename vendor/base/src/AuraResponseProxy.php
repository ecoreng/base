<?php

namespace Base;

class AuraResponseProxy implements \Base\Interfaces\ResponseInterface
{

    protected $response;

    public function __construct(\Aura\Web\Response $response)
    {
        $this->response = $response;
    }

    public function setProtocolVersion($version)
    {
        $this->response->status->setVersion($version);
    }

    public function getStatusCode()
    {
        return $this->response->status->getCode();
    }

    public function setStatus($code, $reasonPhrase = null)
    {
        $this->response->status->setCode($code);
        $this->response->status->setPhrase($reasonPhrase);
    }

    public function getReasonPhrase()
    {
        return $this->response->status->getPhrase();
    }

    public function setHeader($header, $value)
    {
        $this->headers->set($header, $value);
    }

    /**
     * Not really supported
     * 
     * @param type $header
     * @param type $value
     */
    public function addHeader($header, $value)
    {
        $this->headers->set($header, $value);
    }

    /**
     * Remove a specific header by case-insensitive name.
     *
     * @param string $header HTTP header to remove
     * @return void
     */
    public function removeHeader($header)
    {
        $this->headers->set($header, '');
    }

    public function setBody($body)
    {
        $this->content->set($body);
    }

    // delegate all other calls to instance
    public function __call($name, $args)
    {
        return call_user_func_array([$this->response, $name], $args);
    }

    public function __get($attr)
    {
        return $this->response->$attr;
    }

    public function getInstance()
    {
        return $this->response;
    }

}
