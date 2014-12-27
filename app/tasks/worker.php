<?php

namespace Tasks;

use Obullo\Cli\Controller\WorkerController;

/**
 * Worker controller
 */
Class Worker extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $worker = new WorkerController($this->c, func_get_args());
        $worker->run();
    }
}

/* End of file worker.php */
/* Location: .app/tasks/controller/worker.php */