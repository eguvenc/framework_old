<?php

namespace Examples\Console;

use Obullo\Http\Controller;

class Console extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load('console');
    }
}