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
        // $this->session->set('trest', 2);

        // if ($variable = $this->request->get('variable', 'is')->int()) {
        //     echo 'variable is integer';
        // }

        // print_r($this->request->getParsedBody());

        // print_r($this->request->getHeaders());
        // var_dump($this->request->getMethod());

        // echo $this->uri->getUriString();

        // $this->response->json(['dasd']);
        
        // $this->response->withStatus(404);

        // $this->response->html('sds');

        // $this->response->error404('sds');
        
        // $this->response->error('sds');

        // $this->response->emptyContent();

        $this->view->load(
            'hello_world', 
            [
                'title' => 'Hello World !',
            ]
        );
    }

}