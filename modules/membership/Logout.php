<?php

namespace Membership;

class Logout extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['user'];
        $this->c['flash'];
    }

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
        
        $this->flash->info('You succesfully logged out')->with('url')->redirect($this->user['url.login']);
    }
}