<?php

namespace Membership;

Class Logout extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['url'];
        $this->c['user'];
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
        
        $this->url->redirect($this->user->config['url.login']);
    }
}