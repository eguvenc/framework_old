<?php

namespace Http\Middlewares;

use Obullo\Application\Middleware;

class Guest extends Middleware
{
    /**
     * Call action
     * 
     * @return void
     */
    public function call()
    {
        if ($this->user->identity->guest()) {

            $this->flash->info('Your session has been expired.');
            $this->url->redirect('/membership/login/index');
        }
        $this->next->call();
    }
    
}