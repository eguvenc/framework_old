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
        // print_r($this->config->load('maintenance')['subdomain']);

    	// $redis = $this->container->get('redis')->shared(['connection' => 'default']);

    	// var_dump($redis);

    	// $this->database->shared(['connection' => 'default']);

    	// var_dump($this->container->hasShared('database'));

        $this->view->load('views::welcome');
    }
}