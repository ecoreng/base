<?php

namespace Base\Concrete;

use \Psr\Http\Message\RequestInterface as Request;

class DefaultErrorHandler implements \Base\ErrorHandler
{

    public function handle(\Exception $e, Request $request = null)
    {
        try {
            throw $e;
        } catch (\Phroute\Exception\HttpRouteNotFoundException $e) {
            die('Url Not Found');
        } catch (\Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

}
