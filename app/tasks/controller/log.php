<?php

namespace Tasks;

use Obullo\Cli\Controller\LogController;

/**
 * Log command
 */
Class Log extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $log = new LogController($this->c, func_get_args());
        $log->run();
    }
}

/* End of file log.php */
/* Location: .app/tasks/controller/log.php */