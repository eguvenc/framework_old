<?php

defined('STDIN') or die('Access Denied');

use Obullo\Cli\Controller\RouteController;

/**
 * Route controller
 */
Class Route extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $route = new RouteController($this->c, func_get_args());
        $route->run();
    }
}

/* End of file route.php */
/* Location: .app/tasks/controller/route.php */