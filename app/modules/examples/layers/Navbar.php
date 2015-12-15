<?php

namespace Examples\Layers;

use Obullo\Http\Controller;

class Navbar extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {   
        $header = $this->layer->get('views/navbar');
        $footer = $this->layer->get('views/footer');

        $this->view->load(
            'navbar',
            [
                'header' => $header,
                'footer' => $footer,
            ]
        );
    }
}