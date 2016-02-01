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
    	// $redis = $this->container->get('redis')->shared(['connection' => 'default']);

    	// var_dump($redis);


        $this->view->load('views::welcome');
    }
}