<?php

namespace Tests\App;

use Obullo\Http\TestController;

class Middleware extends TestController
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load(
            $this->getViewName(), 
            ['content' => $this->getClassMethods()]
        );
    }

    /**
     * Has middleware
     * 
     * @return void
     */
    public function has()
    {
        $this->container->get('middleware')->add('ParsedBody');
        $this->assertTrue($this->container->get('middleware')->has('ParsedBody'), "I add ParsedBody middleware and i expect that the value is true.");
    }

    /**
     * Add middleware
     * 
     * @return void
     */
    public function add()
    {
        $this->container->get('middleware')->add('TrustedIp');
        $this->assertTrue($this->container->get('middleware')->isAdded('TrustedIp'), "I add TrustedIp middleware and i expect that the value is true.");
    }

    /**
     * Get middleware
     * 
     * @return void
     */
    public function get()
    {
        $this->container->get('middleware')->add('Router');
        $router = $this->container->get('middleware')->get('Router');

        $this->assertInstanceOf('Http\Middlewares\Router', $router, "I add Router middleware and i expect it is an instance of Http\Middlewares\Router object.");
        $this->varDump($router);
    }

    /**
     * Remove middleware
     * 
     * @return void
     */
    public function remove()
    {
        $this->container->get('middleware')->add('TrustedIp');
        $this->container->get('middleware')->remove('TrustedIp');

        $this->assertFalse($this->container->get('middleware')->isAdded('TrustedIp'), "I add TrustedIp middleware then i remove and i expect that the value is false.");
    }

    /**
     * Returns to middleware queue
     * 
     * @return array
     */
    public function getQueue()
    {
        $this->container->get('middleware')->add('TrustedIp');
        $names   = array();
        $objects = array();
        foreach (array_values($this->container->get('middleware')->getQueue()) as $object) {
            $objects[] = $object;
            $names[]   = get_class($object);
        };
        $this->assertInstanceOf(
            "Http\Middlewares\TrustedIp",
            end($objects),
            "I add TrustedIp middleware to end of the queue then i expect it is an instance of Http\Middlewares\TrustedIp object."
        );
        $this->varDump($names);
    }

    /**
     * Returns to all middleware names
     * 
     * @return array
     */
    public function getNames()
    {
        $names = $this->container->get('middleware')->getNames();

        $this->assertContains('App', $names, "I expect middleware names contain App key.");
        $this->varDump($names);
    }

    /**
     * Get regsitered path of middleware
     * 
     * @return string
     */
    public function getPath()
    {
        // Test : getPath
        // 
        // Expected Result :
        // 
        // string(20) "Http\Middlewares\App"

        $path = $this->container->get('middleware')->getPath('App');

        $this->assertEqual('Http\Middlewares\App', $path, "I expect App middleware path equal to Http\Middlewares\App string.");
        $this->varDump($path);
    }

}