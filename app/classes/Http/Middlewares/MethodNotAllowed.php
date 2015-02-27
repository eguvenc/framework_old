<?php

namespace Http\Middlewares;

use Obullo\Container\Container;
use Obullo\Application\Middleware;
use Obullo\Application\Addons\MethodNotAllowedTrait;

class MethodNotAllowed extends Middleware
{
    use MethodNotAllowedTrait;  // You can add / remove addons.

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->methodIsAllowed();
        
        $this->next->load();
    }

    /**
     * Call action
     * 
     * @return void
     */
    public function call()
    {
        $this->next->call();
    }
    
}