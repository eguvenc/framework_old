<?php

namespace Views;

Class Footer extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('view');
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        echo $this->view->get(
            'footer',
            [
                'footer' => '--------------- EXAMPLE FOOTER LAYER ---------------'
            ]
        );
    }
}


/* End of file header.php */
/* Location: .controllers/views/header.php */