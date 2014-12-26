<?php

namespace Tasks;

use Obullo\Cli\Controller\AppController;

/**
 * App controller
 */
Class App extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $route = new AppController($this->c, func_get_args());
        $route->run();
    }
}

/* End of file app.php */
/* Location: .app/tasks/controller/app.php */