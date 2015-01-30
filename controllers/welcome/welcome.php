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
        $this->c->load('layer');
    }

    /**
     * Index
     *
     * @return void
     */
    public function index()
    {
        $this->view->load(
            'welcome',
            [
                'title' => 'Welcome to Obullo !',
                'header' => $this->layer->get('views/header'),
                'footer' => $this->layer->get('views/footer'),
            ]
        );
    }
}

/* End of file welcome.php */
/* Location: .controllers/welcome/welcome.php */