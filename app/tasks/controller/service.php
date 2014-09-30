<?php

defined('STDIN') or die('Access Denied');

use Obullo\Cli\Commands\Service;

/**
 * $app service maintenance control
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function () use ($c) {
        $app = new Service($c, func_get_args());
        $app->run();
    }    
);

/* End of file service.php */
/* Location: .app/tasks/controller/service.php */