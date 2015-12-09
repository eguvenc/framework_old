<?php

namespace Examples\Layers;

use Obullo\Http\Controller;
use View\Base;

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