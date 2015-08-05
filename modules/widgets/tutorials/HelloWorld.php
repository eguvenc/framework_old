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
        $mailResult = $this->mailer
            ->setMailer('mailgun')
            ->from('Obullo\'s <noreply@news.obullo.com>')
            ->to('obullo@yandex.com')
            ->cc('eguvenc@gmail.com')
            ->replyTo('obullo <obullo@yandex.com>')
            ->subject('test')
            ->message("test message")
            ->attach("/var/www/files/logs.jpg")
            ->attach("/var/www/files/debugger.gif")
            ->queue();

        if ($mailResult->hasError()) {
            echo "Fail";
        } else {
            echo "Success !";
        }

        print_r($mailResult->getArray());

        $this->view->load(
            'hello_world', 
            [
                'title' => 'Hello World !',
            ]
        );
    }

}