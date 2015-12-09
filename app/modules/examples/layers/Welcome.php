<?php

namespace Examples\Layers;

use Obullo\Http\Controller;

class Welcome extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {   
        $header = $this->layer->get('views/header');
        $footer = $this->layer->get('views/footer');

        $this->view->load(
            'welcome',
            [
                'header' => $header,
                'footer' => $footer,
            ]
        );
    }
}