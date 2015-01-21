<?php

namespace Base\Concrete;

use \Psr\Http\Message\OutgoingResponseInterface as Response;
use \Aura\Web\Response as AuraResponse;

class AuraResponseAdapter implements Response
{

    protected $response;

    public function __construct(AuraResponse $response)
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

    public function setBody(\Psr\Http\Message\StreamableInterface $body)
    {
        $this->content->set($body);
    }
    
    /**
     * Temprary fix for "setBody" without using SteamableInterface
     * 
     */
    public function setBodyString($body)
    {
        $this->content->set($body);
    }
    
     /**
     * Gets the HTTP protocol version as a string.
     *
     * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion()
    {
        $protocol = $this->response->headers->get('server-protocol');
        $protocol = explode('/', $protocol);
        return end($protocol);
    }

    /**
     * Gets the body of the message.
     *
     * @return StreamableInterface|null Returns the body, or null if not set.
     */
    public function getBody()
    {
        return $this->response->content->get();
    }

    /**
     * Gets all message headers.
     *
     * The keys represent the header name as it will be sent over the wire, and
     * each value is an array of strings associated with the header.
     *
     *     // Represent the headers as a string
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ": " . implode(", ", $values);
     *     }
     *
     *     // Emit headers iteratively:
     *     foreach ($message->getHeaders() as $name => $values) {
     *         foreach ($values as $value) {
     *             header(sprintf('%s: %s', $name, $value), false);
     *         }
     *     }
     *
     * @return array Returns an associative array of the message's headers. Each
     *     key MUST be a header name, and each value MUST be an array of strings.
     */
    public function getHeaders()
    {
        return $this->response->headers->get();
    }

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $header Case-insensitive header name.
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    public function hasHeader($header)
    {
        $header = $this->response->headers->get($header, null);
        return $header !== null;
    }

    /**
     * Retrieve a header by the given case-insensitive name, as a string.
     *
     * This method returns all of the header values of the given
     * case-insensitive header name as a string concatenated together using
     * a comma.
     *
     * NOTE: Not all header values may be appropriately represented using
     * comma concatenation.
     *
     * @param string $header Case-insensitive header name.
     * @return string
     */
    public function getHeader($header)
    {
        return $this->response->headers->get($header);
    }

    /**
     * Retrieves a header by the given case-insensitive name as an array of strings.
     *
     * @param string $header Case-insensitive header name.
     * @return string[]
     */
    public function getHeaderAsArray($header)
    {
        $headerValues = $this->response->headers->get($header, null);
        if (!is_array($headerValues)) {
            $headerValues = [$headerValues];
        }
        return $headerValues;
    }
    
    
    
    // delegate all other calls to instance
    public function __call($name, $args)
    {
        return call_user_func_array([$this->response, $name], $args);
    }

    public function __set($name, $value)
    {
        return $this->response->{$name} = $value;
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
