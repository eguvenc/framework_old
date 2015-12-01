<?php

namespace Examples\Forms;

use Obullo\Http\Controller;

class Forms extends Controller
{
    /**
     * Index
     * 
     * @return void
     */      
    public function index()
    {
        $this->view->load('forms');
    }

}