<?php

namespace Http\Middlewares;

use Obullo\Container\Container;
use Obullo\Application\Middleware;

class Guest extends Middleware
{
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
        if ($this->user->identity->guest()) {

            $this->c['flash']->info('Your session has been expired.');
            $this->c['url']->redirect($this->user->config['url.login']);
        }
        $this->next->call();
    }
    
}