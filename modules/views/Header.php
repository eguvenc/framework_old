<?php

namespace Views;

class Header extends \Controller
{
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
/* Location: .modules/views/header.php */