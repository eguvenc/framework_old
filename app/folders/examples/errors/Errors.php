<?php

namespace Examples\Errors;

use Obullo\Http\Controller;

class Errors extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load('errors');
    }
}