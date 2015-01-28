<?php

namespace Welcome;

Class Welcome extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('url');
        $this->c->load('view');
    }

    /**
     * Index
     *
     * @return void
     */
    public function index()
    {

        die;
        $this->view->load(
            'welcome',
            [
                'title' => 'Welcome to Obullo !'
            ]
        );
    }
}

/* End of file welcome.php */
/* Location: .controllers/welcome/welcome.php */