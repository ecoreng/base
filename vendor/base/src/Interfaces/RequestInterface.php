<?php

namespace Base\Interfaces;

/**
 * Basically PSR-7 IncomingResponseInterface
 */
interface RequestInterface
{

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod();

    /**
     * Retrieves the request URL.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @return string Returns the URL as a string. The URL SHOULD be an absolute
     *     URI as specified in RFC 3986, but MAY be a relative URI.
     */
    public function getUrl();

    /**
     * Retrieve server parameters.
     *
     * Retrieves data related to the incoming request environment, 
     * typically derived from PHP's $_SERVER superglobal. The data IS NOT 
     * REQUIRED to originate from $_SERVER.
     * 
     * @return array
     */
    public function getServerParams();

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
    public function getCookieParams();

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
    public function getQueryParams();

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
    public function getFileParams();

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
    public function getBodyParams();

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
    public function getAttributes();

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
    public function getAttribute($attribute, $default = null);

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
    public function setAttributes(array $attributes);

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
    public function setAttribute($attribute, $value);
}