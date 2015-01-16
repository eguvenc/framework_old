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
    }

    /**
     * Index
     *
     * @return void
     */
    public function index()
    {
        $this->c['view']->load(
            'welcome',
            function () {
                $this->assign('title', 'Welcome to Obullo !');
            }
        );
    }
}

/* End of file welcome.php */
/* Location: .controllers/welcome/welcome.php */