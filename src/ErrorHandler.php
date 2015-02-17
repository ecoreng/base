<?php

namespace Base;

use \Psr\Http\Message\RequestInterface as Request;

interface ErrorHandler
{

    public function handle(\Exception $e, Request $request);
}
