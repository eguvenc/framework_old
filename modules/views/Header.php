<?php

namespace Views;

class Header extends \Controller
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
        echo $this->view->get(
            'header',
            [
                'header' => '<pre>--------------- EXAMPLE HEADER LAYER ---------------</pre>'
            ]
        );
    }
}


/* End of file header.php */
/* Location: .controllers/views/header.php */