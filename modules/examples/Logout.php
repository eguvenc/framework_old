<?php

namespace Examples;

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
        $this->c['config']->load('auth');
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
        
        $this->url->redirect($this->c['config']['auth']['login']['route']);
    }
}