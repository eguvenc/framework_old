<?php

namespace Examples;

use Obullo\Http\Controller;

class Examples extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load('examples');
    }
}