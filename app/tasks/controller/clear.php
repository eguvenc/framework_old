<?php

defined('STDIN') or die('Access Denied');

use Obullo\Cli\Controller\ClearController;

/**
 * Clear command
 */
Class Clear extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $clear = new ClearController($this->c);
        $clear->run();
    }
}

/* End of file clear.php */
/* Location: .app/tasks/controller/clear.php */