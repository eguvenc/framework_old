<?php

defined('STDIN') or die('Access Denied');

/**
 * Queue controller
 */
Class Queue extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->c->load('queue/listener', func_get_args());
    }
}

/* End of file queue.php */
/* Location: .app/tasks/controller/queue.php */