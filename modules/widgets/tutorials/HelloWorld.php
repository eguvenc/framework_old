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
        $result = $this->mailer
            ->from('obullo')
            ->to('1')
            ->subject('test')
            ->message("test message")
            ->send();

        if (! $result->isValid()) {
            print_r($result->getMessages());
        }

        $this->view->load(
            'hello_world', 
            [
                'title' => 'Hello World !',
            ]
        );
    }

}