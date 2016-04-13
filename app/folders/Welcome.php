<?php

use Obullo\Http\Controller;

class Welcome extends Controller
{
    public function __construct()
    {
         // throw new \RuntimeException("asd");
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
       

    	// throw new \RuntimeException("asd");

    	// echo $a;
    	// echo $b;
        $this->view->load('views::welcome');
    }
}