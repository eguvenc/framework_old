<?php

namespace Widgets\Tutorials;

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
        $this->view->load(
            'hello.world', 
            [
                'title' => 'Hello World !',
            ]
        );
    }

}