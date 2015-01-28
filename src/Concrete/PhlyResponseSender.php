<?php

namespace Base\Concrete;

use \Psr\Http\Message\ResponseInterface as Response;

class PhlyResponseSender implements \Base\ResponseSender
{

    protected $response;

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    public function send()
    {
        $this->sendStatus();
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     *
     * Sends the HTTP status.
     *
     * @return null
     *
     */
    protected function sendStatus()
    {
        header('Status: ' . $this->response->getStatusCode() . ' ' . $this->response->getReasonPhrase(), true, $this->response->getStatusCode());
    }

    /**
     *
     * Sends the HTTP headers.
     *
     * @return null
     *
     */
    protected function sendHeaders()
    {
        foreach ($this->response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
    }

    /**
     *
     * Sends the HTTP body by echoing the response content; if the content is a
     * callable, it is invoked and echoed.
     *
     * @return null
     *
     */
    protected function sendContent()
    {
        echo $this->response->getBody();
    }

}
