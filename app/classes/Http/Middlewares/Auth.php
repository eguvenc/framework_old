<?php

namespace Http\Middlewares;

use Obullo\Authentication\Middleware\UniqueLoginTrait;

use Obullo\Http\Middleware\MiddlewareInterface;

class Auth extends MiddlewareInterface
{
    use UniqueLoginTrait;
    
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