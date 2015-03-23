<?php

namespace Widgets\Tutorials;

class Hello_Layout extends \Controller
{
    use \View\Layout\Base;

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['url'];
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
            ]
        );
    }
}

/* End of file hello_layout.php */
/* Location: .controllers/tutorials/hello_layout.php */