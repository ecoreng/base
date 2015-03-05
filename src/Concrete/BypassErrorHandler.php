<?php

namespace Base\Concrete;

use \Psr\Http\Message\RequestInterface as Request;

class BypassErrorHandler implements \Base\ErrorHandler
{

    public function handle(\Exception $e, Request $request = null)
    {
            throw $e;
    }
}
