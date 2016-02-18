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
        $this->view->load(
            'navbar',
            [
                'header' => $this->layer->get('views/navbar'),
                'footer' => $this->layer->get('views/footer'),
            ]
        );
    }
}