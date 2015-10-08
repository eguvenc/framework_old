<?php

namespace Widgets\Tutorials;

class HelloWorld extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */      
    public function index()
    {
        // $this->db;
        // 
        // if ($variable = $this->request->get('variable', 'is')->int()) {
        //     echo 'variable is integer';
        // }

        // print_r($this->request->getParsedBody());

        // $this->response = $this->response->withAddedHeader('Content-Type', 'text/plain');


        // print_r($this->request->getHeaders());
        // var_dump($this->request->getMethod());

        // echo $this->uri->getUriString();


        // echo $this->response->json(['dasd']);


        $this->view->load(
            'hello_world', 
            [
                'title' => 'Hello World !',
            ]
        );



    }

}