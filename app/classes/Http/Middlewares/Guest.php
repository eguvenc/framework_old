<?php

namespace Http\Middlewares;

use Obullo\Application\Middleware;
use Obullo\Authentication\AuthConfig;

class Guest extends Middleware
{
    /**
     * User service
     * 
     * @var object
     */
    protected $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = $this->c->get(
            'user',
            [
                'table' => AuthConfig::session('db.tablename')
            ]
        );
    }

    /**
     * Call action
     * 
     * @return void
     */
    public function call()
    {
        if ($this->user->identity->guest()) {

            $this->flash->info('Your session has been expired.');
            $this->url->redirect(AuthConfig::get('url.login'));
        }
        $this->next->call();
    }
    
}