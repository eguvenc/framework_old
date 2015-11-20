<?php

namespace Widgets\Tutorials;

use Obullo\Http\Controller;

class HelloWorld extends Controller
{
    /**
     * Index
     * 
     * @middleware->method('GET');
     * 
     * @return void
     */ 
    public function index()
    {
        // var_dump($this->request->getParsedBody());

        // echo $a;
        // throw new \RuntimeException("test");

        // echo $this->request->getIpAddress();

        // if ($variable = $this->request->get('variable', 'is')->int()) {
        //     echo 'variable is integer';
        // }

        // print_r($this->request->getParsedBody());

        $this->view->load(
            'hello_world', 
            [
                'title' => 'Hello World !',
            ]
        );
    }

}