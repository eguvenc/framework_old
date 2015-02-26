<?php

namespace Http\Middlewares;

use Obullo\Container\Container;
use Obullo\Application\Middleware;

class Csrf extends Middleware
{
    /**
     * Csrf
     * 
     * @var object
     */
    protected $csrf;

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->csrf = $this->c['security/csrf'];
        $this->next->load();
    }

    /**
     *  Call action
     * 
     * @return void
     */
    public function call()
    {
        $this->csrf->init();
        $this->csrf->verify(); // Csrf protection check
        
        $this->next->call();
    }
}