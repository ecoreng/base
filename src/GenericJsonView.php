<?php

namespace Base;

class GenericJsonView extends \ArrayObject implements \Base\Interfaces\ViewInterface
{

    public function __construct()
    {
        parent::__construct([]);
    }

    public function render($template = null, array $data = array(), $prefix = null)
    {
        return json_encode(array_merge((array) $this, $data));
    }

}
