<?php

namespace Base\Test;

class Config extends \PHPUnit_Framework_TestCase
{

    protected $config;

    public function setUp()
    {
        $this->config = new \Base\Concrete\Config(['foo' => 'bar']);
    }

    public function testGetPreset()
    {
        $this->assertEquals('bar', $this->config->get('foo'));
        $this->assertEquals('bar', $this->config['foo']);
    }
    
    public function testSetGet()
    {
        $this->config->set('woo', 'tang');
        $this->assertEquals('tang', $this->config->get('woo'));
        $this->assertEquals('tang', $this->config['woo']);
    }
    
    public function testHas()
    {
        $this->assertEquals(true, $this->config->has('foo'));
        $this->assertEquals(true, isset($this->config['foo']));
        
        $this->assertEquals(false, $this->config->has('woo'));
        $this->assertEquals(false, isset($this->config['woo']));
    }
    
    public function testDelete()
    {
        $this->assertEquals(true, $this->config->has('foo'));
        $this->config->delete('foo');
        $this->assertEquals(false, $this->config->has('foo'));
        
        $this->config['foo'] = 'bar';
        $this->assertEquals(true, isset($this->config['foo']));
        unset($this->config['foo']);
        $this->assertEquals(false, isset($this->config['foo']));
    }
    
    public function testIterator()
    {
        $this->assertInstanceOf('\ArrayIterator', $this->config->getIterator());
    }

}
