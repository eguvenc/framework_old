<?php

defined('STDIN') or die('Access Denied');

use Obullo\Cli\Commands\Log;

/**
 * $app log
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function () use ($c) {

    	// print_r(func_get_args());
        $log = new Log($c, func_get_args());
        $log->run();
    }
);

/* End of file log.php */
/* Location: .app/tasks/controller/log.php */