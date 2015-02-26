<?php

namespace Http\Middlewares;

use Obullo\Container\Container;
use Obullo\Application\Middleware;
use Obullo\Application\Addons\RewriteHttpsTrait;

class Https extends Middleware
{
    use RewriteHttpsTrait;  // You can add / remove addons.

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {   
        $this->rewrite();

        $this->next->load();
    }

    /**
     *  Call action
     * 
     * @return void
     */
    public function call()
    {
        $this->next->call();
    }
}