<?php

namespace Views;

Class Header extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['view'];
    }
    
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        echo $this->view->nested($this)->get(
            'header',
            [
                'header' => '--------------- EXAMPLE HEADER LAYER ---------------'
            ]
        );
    }
}


/* End of file header.php */
/* Location: .controllers/views/header.php */