<?php

namespace Http\Middlewares;

use Obullo\Application\Middleware;
use Obullo\Application\Middlewares\MethodNotAllowedTrait;

class MethodNotAllowed extends Middleware
{
    use MethodNotAllowedTrait;
    
    /**
     * Loader
     *
     * @param array $params allowed methods
     * 
     * @return void
     */
    public function __construct(array $params)
    {
        $this->check($params);
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