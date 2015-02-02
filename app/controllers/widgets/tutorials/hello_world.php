<?php

namespace Widgets\Tutorials;

Class Hello_World extends \Controller
{
    use \View\Layout\Welcome;

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('view');
        $this->c->load('service provider cache', ['driver' => 'redis']);

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

/* End of file hello_world.php */
/* Location: .controllers/widgets/tutorials/hello_world.php */