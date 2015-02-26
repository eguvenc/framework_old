<?php

namespace Http\Middlewares;

use Obullo\Container\Container;
use Obullo\Application\Middleware;
use Obullo\Application\Addons\UnderMaintenanceTrait;

class Maintenance extends Middleware
{
    use UnderMaintenanceTrait; // You can add / remove addons.

    /**
     * Injected parameters
     * 
     * @var object
     */
    public $params = array();

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->domainIsDown();

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