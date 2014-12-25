<?php

namespace Widgets\Tutorials;

/**
 * Widgets
 */
Class Hello_World extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('view');
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
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('footer', $this->template('footer'));
            }
        );
    }
}

/* End of file hello_world.php */
/* Location: .public/widgets/tutorials/controller/hello_world.php */