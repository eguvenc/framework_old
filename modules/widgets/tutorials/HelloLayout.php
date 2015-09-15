<?php

namespace Widgets\Tutorials;

class HelloLayout extends \Controller
{
    use \View\Base;

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load(
            'hello_layout',
            [
                'title' => 'Hello Layouts !'
            ]
        );
    }
}