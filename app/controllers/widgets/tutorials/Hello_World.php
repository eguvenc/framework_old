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