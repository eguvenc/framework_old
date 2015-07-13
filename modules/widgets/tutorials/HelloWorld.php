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
        $this->logger->emergency("emerg test 1");
        $this->logger->info("info test 1");
        $this->logger->info("info test 2");
        $this->logger->info("info test 3");
        $this->logger->info("info test 4");
        $this->logger->notice("NOTICE XXXX", array());
        $this->logger->info("info test 5");

        // $this->logger->info("info test");
        // $this->logger->info("info test");
        // $this->logger->info("info test");
        // $this->logger->info("info test");
        // $this->logger->info("info test");
        // $this->logger->info("info test");
        // $this->logger->info("info test");
        // $this->logger->info("info test");
        // $this->logger->info("info test");
        // $this->logger->info("info test");

        // $this->logger->load('mongo');
        // $this->logger->channel('security');               
        // $this->logger->alert('Possible hacking attempt !', array('username' => 'test'));
        // $this->logger->info('info test.', array('username' => 'test'));
        // $this->logger->push();

        // $this->logger->alert("ENDtest");
        // $this->logger->notice("ENDtest2");
        // $this->logger->alert("ENDtest3");
        
        $this->view->load(
            'hello_world', 
            [
                'title' => 'Hello World !',
            ]
        );
    }

}