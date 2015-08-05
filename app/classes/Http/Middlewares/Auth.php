<?php

namespace Http\Middlewares;

use Obullo\Application\Middleware;
use Obullo\Authentication\Middleware\UniqueLoginTrait;

class Auth extends Middleware
{
    use UniqueLoginTrait;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = $this->c->get('user', ['table' => 'users']);
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
            
            // $this->user->activity->set('last', time());

        }
        $this->next->call();
    }
    
}