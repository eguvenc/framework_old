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


        // print_r($this->request->getHeaders());
        // var_dump($this->request->getMethod());

        // echo $this->uri->getUriString();

        // print_r($this->response->getHeaders());
        // exit;

        // $mailResult = $this->mailer
        //     ->setProvider('mandrill')
        //     ->from('Obullo\'s <noreply@news.obullo.com>')
        //     ->to('obullo@yandex.com')
        //     ->cc('eguvenc@gmail.com')
        //     ->replyTo('obullo <obullo@yandex.com>')
        //     ->subject('test')
        //     ->message("test message")
        //     ->attach("/var/www/files/logs.jpg")
        //     ->attach("/var/www/files/debugger.gif")
        //     ->queue();

        // if ($mailResult->hasError()) {
        //     echo "Fail";
        // } else {
        //     echo "Success !";
        // }

        $this->view->load(
            'hello_world', 
            [
                'title' => 'Hello World !',
            ]
        );
    }

}