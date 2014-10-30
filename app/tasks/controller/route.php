<?php

defined('STDIN') or die('Access Denied');

use Obullo\Cli\Commands\Route;

/**
 * $app host maintenance control
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function () use ($c) {
        $app = new Route($c, func_get_args());
        $app->run();
    }    
);

/* End of file route.php */
/* Location: .app/tasks/controller/route.php */