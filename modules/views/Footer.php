<?php

namespace Views;

class Footer extends \Controller
{
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
                'footer' => '<pre>--------------- EXAMPLE FOOTER LAYER ---------------</pre>'
            ]
        );
    }
}


/* End of file header.php */
/* Location: .modules/views/header.php */