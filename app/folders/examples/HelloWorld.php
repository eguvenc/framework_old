<?php

namespace Examples;

use Obullo\Http\Controller;

class HelloWorld extends Controller
{
    /**
     * Index
     * 
     * @return void
     */ 
    public function index()
    {       
        $this->view->load('hello_world');
    }

}