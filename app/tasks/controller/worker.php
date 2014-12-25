<?php

namespace Tasks;

/**
 * Worker controller
 */
Class Worker extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('queue/worker as worker');
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->worker->init(func_get_args());
        $this->worker->pop();
    }
}

/* End of file worker.php */
/* Location: .app/tasks/controller/worker.php */