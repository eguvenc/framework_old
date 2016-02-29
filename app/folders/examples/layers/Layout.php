<?php

namespace Examples\Layers;

use View\Base;
use Obullo\Http\Controller;

class Layout extends Controller
{
    use Base;

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {   
        $this->view->load('layout');
    }
}