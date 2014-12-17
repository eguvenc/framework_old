<?php

Class Logout extends Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('url');
        $this->c->load('service/user');
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
        
        $this->url->redirect('/examples/login');
    }
}


/* End of file logout.php */
/* Location: .public/tutorials/controller/logout.php */