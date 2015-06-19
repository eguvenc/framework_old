<?php

namespace Membership;

use Obullo\Authentication\AuthConfig;

class Logout extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->user->identity->logout();

        // $this->user->identity->destroy();
        // $this->user->identity->forgetMe();
        
        $this->flash->info('You succesfully logged out')->url->redirect(AuthConfig::get('url.login'));
    }
}