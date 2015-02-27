<?php

namespace Http\Middlewares;

use Obullo\Application\Middleware;
use Obullo\Application\Addons\BenchmarkTrait;
use Obullo\Application\Addons\SanitizeSuperGlobalsTrait;

class Request extends Middleware
{
    use BenchmarkTrait;
    use SanitizeSuperGlobalsTrait;   // You can add / remove addons.

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->next->load();
    }

    /**
     *  Call action
     * 
     * @return void
     */
    public function call()
    {      
        $this->sanitize();
        $this->benchmarkStart();

        $this->next->call();

        $this->benchmarkEnd();
    }


}