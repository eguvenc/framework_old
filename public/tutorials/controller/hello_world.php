<?php

Class Hello_World extends Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('view');
        $this->c->load('service/queue');

        $this->queue->channel('Log');
        // $this->queue->push('Workers/Logger', 'Logger', array('log' => array('debug' => 'Test')));

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
/* Location: .public/tutorials/controller/hello_world.php */