<?php

namespace Http\Middlewares;

use Obullo\Application\Middleware;
use Obullo\Authentication\AuthConfig;
use Obullo\Application\Middlewares\BenchmarkTrait;
use Obullo\Application\Middlewares\SanitizerTrait;

class Request extends Middleware
{
    use BenchmarkTrait;
    use SanitizerTrait;

    /**
     * User service
     * 
     * @var object
     */
    protected $user;

    /**
     * Top Level Constructor
     */
    public function __construct()
    {
        $this->user = $this->c->get(
            'user',
            [
                'table' => 'users'
            ]
        );
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

        $this->c['logger']->shutdown();
    }

}