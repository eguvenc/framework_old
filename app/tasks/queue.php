<?php

namespace Tasks;

use Obullo\Cli\Controller\QueueController;

/**
 * Queue controller
 */
Class Queue extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $queue = new QueueController($this->c, func_get_args());
        $queue->run();
    }
}

/* End of file queue.php */
/* Location: .app/tasks/controller/queue.php */