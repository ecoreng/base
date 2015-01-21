<?php

namespace Base\Concrete;

use \Base\View;

class GenericJsonView extends \ArrayObject implements View
{

    public function __construct()
    {
        parent::__construct([]);
    }

    public function render($template = null, array $data = array())
    {
        return json_encode(array_merge((array) $this, $data));
    }

}
