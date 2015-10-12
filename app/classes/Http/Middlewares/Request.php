<?php

namespace Http\Middlewares;

use Obullo\Application\Middleware;
use Obullo\Http\BenchmarkTrait;

class Request extends Middleware
{
    use BenchmarkTrait;

    /**
     *  Call action
     * 
     * @return void
     */
    public function call()
    {
        $this->benchmarkStart();

        $this->next->call();
        
        $this->benchmarkEnd();
    }

}