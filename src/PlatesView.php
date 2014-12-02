<?php

namespace Base;

use \League\Plates\Engine;

class PlatesView extends \ArrayObject implements \Base\Interfaces\ViewInterface
{

    /**
     * Plates Engine with public visibility
     * @var \League\Plates
     */
    public $engine;
    
    /**
     * construct with Plates Engine as param
     * 
     * @param \League\Plates\Engine $engine
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Return rendered template with data
     * 
     * @param string $template
     * @param array $data
     * @return string
     */
    public function render($template, array $data = array())
    {
        return $this->engine->render($template, array_merge((array) $this, $data));
    }
}
