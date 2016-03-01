<?php

namespace Tests\App;

use Obullo\Http\Controller;

class Middleware extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $methods = get_class_methods($this);
        foreach ($methods as $name) {
            if (! in_array($name, ['index', 'setContainer', 'getContainer', '__get','__set']))
            echo $this->url->anchor(rtrim($this->request->getRequestTarget(), "/")."/".$name, $name)."<br>";
        }
    }

    /**
     * Has middleware
     * 
     * @return void
     */
    public function has()
    {
        // Test : Router
        // 
        // Expected Result :
        // 
        // true
        
        var_dump($this->container->get('middleware')->has('Router'));
    }

    /**
     * Add middleware
     * 
     * @return void
     */
    public function add()
    {
        // Test : TrustedIp
        // 
        // Expected Result :
        // 
        // true
        
        $this->container->get('middleware')->add('TrustedIp');

        var_dump($this->container->get('middleware')->isAdded('TrustedIp'));
    }

    /**
     * Get middleware
     * 
     * @return void
     */
    public function get()
    {
        // Test : Router
        // 
        // Expected Result :
        // 
        // object(Http\Middlewares\Router)
        
        var_dump($this->container->get('middleware')->get('Router'));
    }

    /**
     * Remove middleware
     * 
     * @return void
     */
    public function remove()
    {
        // Test : TrustedIp
        // 
        // Expected Result :
        // 
        // false
        
        $this->container->get('middleware')->add('TrustedIp');
        $this->container->get('middleware')->remove('TrustedIp');

        var_dump($this->container->get('middleware')->isAdded('TrustedIp'));
    }

    /**
     * Returns to middleware queue
     * 
     * @return array
     */
    public function getQueue()
    {
        // Test : default queue
        // 
        // Expected Result :
        // 
        // array(3) { [0]=> object(Http\Middlewares\Router)#48 (1) { ["con ....

        var_dump(array_values($this->container->get('middleware')->getQueue()));
    }

    /**
     * Returns to all middleware names
     * 
     * @return array
     */
    public function getNames()
    {
        // Test : names
        // 
        // Expected Result :
        // 
        // array(3) { [0]=> string(6) "Router" [1]=> string(3) "App" [2]=> string(5) "Error" }

        var_dump(array_values($this->container->get('middleware')->getNames()));
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

        var_dump($this->container->get('middleware')->getPath('App'));
    }

}