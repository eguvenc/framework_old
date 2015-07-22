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

        // echo $result = $this->mailer
        //     ->from('Ersin <localhost@localdomain.com>')
        //     ->to('eguvenc@gmail.com')
        //     ->subject('test')
        //     ->message("test message")
        //     ->getFrom();

        return;

        if (! $result->isValid()) {
            print_r($result->getMessages());
        } else {
            echo "Success !";
        }

        $this->view->load(
            'hello_world', 
            [
                'title' => 'Hello World !',
            ]
        );
    }

}