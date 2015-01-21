<?php

namespace Base\Concrete;

use \Psr\Http\Message\IncomingRequestInterface as Request;

class AuraRequestAdapter implements Request
{

    protected $request;
    protected $attributes = [];

    public function __construct(\Aura\Web\Request $request)
    {
        $this->request = $request;
    }

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod()
    {
        return $this->request->method->get();
    }

    /**
     * Retrieves the request URL.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @return string Returns the URL as a string. The URL SHOULD be an absolute
     *     URI as specified in RFC 3986, but MAY be a relative URI.
     */
    public function getUrl()
    {
        return $this->request->url->get();
    }

    /**
     * Retrieve server parameters.
     *
     * Retrieves data related to the incoming request environment, 
     * typically derived from PHP's $_SERVER superglobal. The data IS NOT 
     * REQUIRED to originate from $_SERVER.
     * 
     * @return array
     */
    public function getServerParams()
    {
        return $this->request->server->get();
    }

    /**
     * Retrieve cookies.
     *
     * Retrieves cookies sent by the client to the server.
     *
     * The assumption is these are injected during instantiation, typically
     * from PHP's $_COOKIE superglobal. The data IS NOT REQUIRED to come from
     * $_COOKIE, but MUST be compatible with the structure of $_COOKIE.
     *
     * @return array
     */
    public function getCookieParams()
    {
        return $this->request->cookies->get();
    }

    /**
     * Retrieve query string arguments.
     *
     * Retrieves the deserialized query string arguments, if any.
     *
     * These values SHOULD remain immutable over the course of the incoming
     * request. They MAY be injected during instantiation, such as from PHP's
     * $_GET superglobal, or MAY be derived from some other value such as the
     * URI. In cases where the arguments are parsed from the URI, the data
     * MUST be compatible with what PHP's `parse_str()` would return for
     * purposes of how duplicate query parameters are handled, and how nested
     * sets are handled.
     *
     * @return array
     */
    public function getQueryParams()
    {
        return $this->request->query->get(PHP_URL_QUERY);
    }

    /**
     * Retrieve the upload file metadata.
     *
     * This method MUST return file upload metadata in the same structure
     * as PHP's $_FILES superglobal.
     *
     * These values SHOULD remain immutable over the course of the incoming
     * request. They MAY be injected during instantiation, such as from PHP's
     * $_FILES superglobal, or MAY be derived from other sources.
     *
     * @return array Upload file(s) metadata, if any.
     */
    public function getFileParams()
    {
        return $this->request->files->get();
    }

    /**
     * Retrieve any parameters provided in the request body.
     *
     * If the request body can be deserialized to an array, this method MAY be
     * used to retrieve them. These MAY be injected during instantiation from
     * PHP's $_POST superglobal. The data IS NOT REQUIRED to come from $_POST,
     * but MUST be an array.
     *
     * @return array The deserialized body parameters, if any.
     */
    public function getBodyParams()
    {
        // or any other http method from the content;
        // right now it's just post
        return $this->request->post->get();
    }

    /**
     * Retrieve attributes derived from the request.
     *
     * The request "attributes" may be used to allow injection of any
     * parameters derived from the request: e.g., the results of path
     * match operations; the results of decrypting cookies; the results of
     * deserializing non-form-encoded message bodies; etc. Attributes
     * will be application and request specific, and CAN be mutable.
     *
     * @return array Attributes derived from the request.
     */
    public function getAttributes()
    {
        return $this->request->attributes;
    }

    /**
     * Retrieve a single derived request attribute.
     * 
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     * 
     * @see getAttributes()
     * @param string $attribute Attribute name.
     * @param mixed $default Default value to return if the attribute does not exist.
     * @return mixed
     */
    public function getAttribute($attribute, $default = null)
    {
        if (array_key_exists($attribute, $this->request->attributes)) {
            return $this->request->attributes[$attribute];
        } else {
            return $default;
        }
    }

    /**
     * Set attributes derived from the request.
     *
     * This method allows setting request attributes, as described in
     * getAttributes().
     *
     * @see getAttributes()
     * @param array $attributes Attributes derived from the request.
     * @return void
     */
    public function setAttributes(array $attributes)
    {
        $this->request->attributes = $attributes;
    }

    /**
     * Set a single derived request attribute.
     * 
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     *
     * @see getAttributes()
     * @param string $attribute The attribute name.
     * @param mixed $value The value of the attribute.
     * @return void
     */
    public function setAttribute($attribute, $value)
    {
        $this->request->attributes[$attribute] = $value;
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
        $protocol = $this->request->server['SERVER_PROTOCOL'];
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
        return $this->request->content->getRaw();
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
        return $this->request->headers->get();
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
        $header = $this->request->headers->get($header, null);
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
        return $this->request->headers->get($header);
    }

    /**
     * Retrieves a header by the given case-insensitive name as an array of strings.
     *
     * @param string $header Case-insensitive header name.
     * @return string[]
     */
    public function getHeaderAsArray($header)
    {
        $headerValues = $this->request->headers->get($header, null);
        if (!is_array($headerValues)) {
            $headerValues = [$headerValues];
        }
        return $headerValues;
    }

    /**
     * Delegate all other calls to request instance
     * 
     */
    public function __call($name, $args)
    {
        return call_user_func_array([$this->request, $name], $args);
    }

    public function __get($attr)
    {
        return $this->request->$attr;
    }
    
    public function __set($name, $value)
    {
        return $this->request->{$name} = $value;
    }

    public function getInstance()
    {
        return $this->request;
    }

}
