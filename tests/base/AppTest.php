<?php

namespace Base\Test;

use \Base\Concrete\DefaultServiceRegisterer as Services;
use \Base\Concrete\Container as BContainer;
use \Base\Concrete\App;

class AppTest extends \PHPUnit_Framework_TestCase
{

    protected $app;

    public function setUp()
    {
        $autoloader = $this->getMock('\Composer\Autoload\ClassLoader');
        $c = new BContainer;
        $c->register(new Services($autoloader));
        $this->app = $c->get('Base\App');
    }

    public function setTestRoute()
    {
        $this->app->addRoute('GET', ['/test/{id}', 'name'], function($id) {
            echo 'test:' . $id;
        });
    }

    public function testInterfaceIsApp()
    {
        $this->assertInstanceOf('Base\App', $this->app);
    }

    public function testRouterInstance()
    {
        $this->assertInstanceOf('Base\Router', $this->app->getRouter());
    }

    public function testAddGetRoute()
    {
        $this->setTestRoute();
        $route = $this->app->getRoute('name', ['id' => 9]);
        $this->assertEquals($route, 'test/9');
    }

    public function testAddMiddleware()
    {
        $r = new \ReflectionObject($this->app);
        $rm = $r->getProperty('middleware');
        $rm->setAccessible(true);

        $mw = $this->getMockBuilder('Base\Middleware')
                ->setMethods([
                    'call',
                    'setApplication',
                    'getApplication',
                    'setInjector',
                    'getInjector',
                    'setNextMiddleware',
                    'getNextMiddleware'
                ])
                ->getMock();

        $mw->expects($this->once())
                ->method('call');

        $this->app->add($mw);
        $this->app->run();

        $mws = $rm->getValue($this->app);
        $appMw = reset($mws);
        $this->assertSame($mw, $appMw);
    }

    public function testCall()
    {
        $this->setTestRoute();

        $r = new \ReflectionObject($this->app);
        $rm = $r->getMethod('dispatch');
        $rm->setAccessible(true);
        $req = (new \Base\Concrete\AuraMessageFactoryAdapter([
            '_SERVER' => [
                'REQUEST_URI' => '/test/22',
                'REQUEST_METHOD' => 'GET'
            ]
                ]))->newIncomingRequest();

        $res = $rm->invoke($this->app, $req, false);

        $this->assertInstanceOf('Psr\Http\Message\OutgoingResponseInterface', $res);
        $this->assertEquals('test:22', $res->content->get());
        $this->assertEquals(200, $res->status->getCode());
    }

    public function testCallEmpty()
    {
        try {
            $this->app->call();
        } catch (\Exception $e) {
            $this->assertInstanceOf('Phroute\Exception\HttpRouteNotFoundException', $e);
        }
    }

    public function testSubRequest()
    {
        $this->setTestRoute();
        $res = $this->app->subRequest('/test/22', []);
        $this->assertInstanceOf('Psr\Http\Message\OutgoingResponseInterface', $res);
        $this->assertEquals('test:22', $res->content->get());
        $this->assertEquals(200, $res->status->getCode());
    }

    public function testDefaultConfig()
    {
        $config = [];
        $config[] = $this->app->getConfig('environment.base-url');
        $config[] = $this->app->getConfig('app.mode');

        $this->assertEquals($config[0], '');
        $this->assertEquals($config[1], 'dev');
    }

    public function testSetGetConfig()
    {
        $this->app->setConfig('foo', 'bar');
        $this->assertEquals($this->app->getConfig('foo'), 'bar');
    }

    public function testSetConfigArray()
    {
        $config = [
            'foo' => 'bar',
            'test' => 1,
            'environment.base-url' => 'test1'
        ];
        $this->app->setConfigArray($config);
        $this->assertEquals($this->app->getConfig('foo'), $config['foo']);
        $this->assertEquals($this->app->getConfig('test'), $config['test']);
        $this->assertEquals($this->app->getConfig('environment.base-url'), $config['environment.base-url']);
    }

}
