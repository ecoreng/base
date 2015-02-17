<?php

namespace ExampleCo\Example;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class CustomMiddleware extends \Base\Concrete\Middleware implements \Base\Middleware
{

    protected $config;

    public function __construct(\Base\Config $config)
    {
        $this->config = $config;
    }

    public function call(Request $request, Response $response)
    {
        $base = $this->config->get('environment.base-url');
        $url = str_replace($base, '', $request->getUri()->getPath());
        if ($url === '') {
            $response = $response->withStatus(401, 'NOPE');
            $response->getBody()->write('<h1>Unauthorized</h1>');
            return $response;
        }
        // let it pass
        return $this->next($request, $response);
    }

}
