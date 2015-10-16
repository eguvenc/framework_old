<?php

namespace Http\Middlewares;

use Obullo\Application\Middleware;
use Obullo\Application\Middlewares\UnderMaintenanceTrait;

class Maintenance extends Middleware
{
    use UnderMaintenanceTrait;

    /**
     * Constructor
     *
     * @param array $params domain parameters
     * 
     * @return void
     */
    public function __construct(array $params)
    {   
        $this->check($params);
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