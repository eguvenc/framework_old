<?php

namespace Widgets\Tutorials;

Class Hello_World extends \Controller
{
     use \View\Layout\Base;

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        // $this->view->assign('footer', $this->layer->get('views/footer'));

        // $this->cache = $this->c['service provider cache']->get(['driver' => 'redis']);

        // $this->c['service provider cache']->get(['driver' => 'redis']);
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index($id = 2)
    {
        echo $id;

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