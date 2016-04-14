<?php

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
        // echo str_replace('test', '', "test.exampletest.com");

        // echo $this->router->getDomain()->getBaseHost();
        // $subname = $this->router->getDomain()->getSubName('t.framework.com');

        // var_dump($subname);
       
    	// throw new \RuntimeException("asd");

    	// echo $a;
    	// echo $b;
        $this->view->load('views::welcome');
    }
}