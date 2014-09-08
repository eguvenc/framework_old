<?php

defined('STDIN') or die('Access Denied');

use Obullo\Console\Commands\Log;

/**
 * $app log
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function () use ($c) {
        $log = new Log($c, func_get_args());
        $log->run();
    }
);

/* End of file log.php */
/* Location: .app/tasks/controller/log.php */