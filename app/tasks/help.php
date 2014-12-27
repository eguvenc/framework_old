<?php

namespace Tasks;

use Obullo\Cli\Controller\HelpController;

/**
 * Help controller
 */
Class Help extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $help = new HelpController($this->c);
        $help->run();
    }
}

/* End of file help.php */
/* Location: .app/tasks/controller/help.php */