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
    function ($route = 'app') use ($c) {
        $log = new Log($c, array('route' => $route));
        $log->run();
    }
);

/* End of file log.php */
/* Location: .app/tasks/controller/log.php */