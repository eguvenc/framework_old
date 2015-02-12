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

        // $this->c['service provider cache']->get(['driver' => 'redis']);
        
        // $this->mailer = $this->c['service provider mailer']->get(['driver' => 'smtp', 'options' => array('queue' => true)]);

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