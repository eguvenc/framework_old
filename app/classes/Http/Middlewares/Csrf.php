<?php

namespace Http\Middlewares;

use Obullo\Container\Container;
use Obullo\Application\Middleware;

class Csrf extends Middleware
{
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
        echo 'CSRF';
        // if ( ! $this->c['csrf']->verify() AND ! $this->c['request']->isAjax()) {

        //     $this->c['response']->showError(
        //         'The action you have requested is not allowed.', 
        //         401, 
        //         'Access Denied'
        //     );
        // }
        $this->next->call();
    }
    
}