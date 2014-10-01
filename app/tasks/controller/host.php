<?php

defined('STDIN') or die('Access Denied');

use Obullo\Cli\Commands\Host;

/**
 * $app host maintenance control
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function () use ($c) {
        $app = new Host($c, func_get_args());
        $app->run();
    }    
);

/* End of file host.php */
/* Location: .app/tasks/controller/host.php */