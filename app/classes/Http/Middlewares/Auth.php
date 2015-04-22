<?php

namespace Http\Middlewares;

use Obullo\Container\Container;
use Obullo\Application\Middleware;
use Obullo\Authentication\Middleware\UniqueLoginTrait;

class Auth extends Middleware
{
    use UniqueLoginTrait;

    /**
     * User service
     * 
     * @var object
     */
    protected $user;

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->user = $this->c['user'];
        $this->next->load();
    }

    /**
     *  Call action
     * 
     * @return void
     */
    public function call()
    {
        if ($this->user->identity->check()) {
            
            $this->uniqueLoginCheck();  // Terminate multiple logins

            $this->user->activity->set('date', time());  //  Example meta data
        }
        $this->next->call();
    }
    
}