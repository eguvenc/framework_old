<?php

namespace Widgets\Tutorials;

Class Hello_World extends \Controller
{
     // use \View\Layout\Base;

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['view'];
        $this->c['session'];

        // $this->cache = $this->c['service provider cache']->get(['driver' => 'memcached', 'connection' => 'default']);

        // $this->cache->set('test', '1245');
        // echo $this->cache->get('test');


        // echo $_SERVER['REQUEST_URI'];

        // $this->c['translator']->load('validator');

        // $this->AMQPConnection = $this->c['service provider AMQP']->get(['connection' => 'default']);

        // $this->c['service provider cache']->get(['driver' => 'redis']);
        
        // $this->mailer = $this->c['service provider mailer']->get(['driver' => 'mandrill', 'options' => array('queue' => true)]);

        // $this->mailer->from('admin@example.com');
        // $this->mailer->to('eguvenc@gmail.com');
        // $this->mailer->subject('Test');
        // $this->mailer->message('Hello_World !');
        // $this->mailer->send();
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load(
            'hello_world', 
            [
                'title' => 'Hello World !',
            ]
        );
    }

}