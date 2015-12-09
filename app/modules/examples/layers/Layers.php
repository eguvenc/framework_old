<?php

namespace Examples\Layers;

use Obullo\Http\Controller;

class Layers extends Controller
{
    /**
     * Index
     * 
     * @return void
     */      
    public function index()
    {
        $this->view->load('layers');
    }

}