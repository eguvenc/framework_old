<?php

namespace Widgets\Tutorials;

Class Hello_Layout extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['url'];
        $this->c['view'];
    }

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
            ],
            'welcome'
        );
    }
}

/* End of file hello_layout.php */
/* Location: .controllers/tutorials/hello_layout.php */