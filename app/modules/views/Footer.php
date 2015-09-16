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