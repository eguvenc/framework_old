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
        
        // $this->response->show404('sds');
        
        // $this->response->showError('sds');
        // 

        $this->view->load(
            'hello_world', 
            [
                'title' => 'Hello World !',
            ]
        );
    }

}