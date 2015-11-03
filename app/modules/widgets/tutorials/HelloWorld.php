<?php

namespace Widgets\Tutorials;

use Obullo\Http\Controller;

class HelloWorld extends Controller
{
    /**
     * Index
     * 
     * @middleware->method("post");
     * 
     * @return void
     */ 
    public function index()
    {
        // throw new \ErrorException("asd");

        // echo $this->request->getUri()->getUriString();

        // echo $this->url->baseUrl();

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
        
        // $this->middleware->add('NotFound');

        // $this->response->redirect('http://framework/welcome');

        // return $this->response->emptyContent();

        $this->view->load(
            'hello_world', 
            [
                'title' => 'Hello World !',
            ]
        );
    }

}