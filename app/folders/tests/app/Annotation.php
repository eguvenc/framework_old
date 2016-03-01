<?php

namespace Tests\App;

use Obullo\Http\Controller;

class Annotation extends Controller
{
    /**
     * Index (Enable annotations from config.php file !!!!)
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
     * Index
     *
     * @middleware->method('put', 'delete')
     * 
     * @return void
     */
    public function method()
    {
        // Enable annotations from config.php file !!!!
        // 
        // Expected Result :
        // 
        // Error
        // 
        // GET Method Not Allowed
    }

    /**
     * Add
     *
     * @middleware->add('Guest')
     * 
     * @return void
     */
    public function add()
    {
        // Add middleware test ( Guest )
        // 
        // Expected Result :
        // 
        // http://framework/examples/membership/login/index
        // 
        // Your session has been expired.
    }

    /**
     * Remove
     *
     * @middleware->when('post', 'put', 'delete')->add('Guest')
     * 
     * @return void
     */
    public function whenPost()
    {
        // When add test ( Guest )
        // 
        // Expected Result :
        // 
        // empty content
    }

    /**
     * Remove
     *
     * @middleware->when('get')->add('Guest')
     * 
     * @return void
     */
    public function whenGet()
    {
        // When add test ( Guest )
        // 
        // Expected Result :
        // 
        // http://framework/examples/membership/login/index
        // 
        // Your session has been expired.
    }

    /**
     * Remove
     *
     * @middleware->add('Guest')
     * @middleware->remove('Guest')
     * 
     * @return void
     */
    public function remove()
    {
        // Remove middleware test ( Guest )
        // 
        // Expected Result :
        // 
        // empty content
    }

}