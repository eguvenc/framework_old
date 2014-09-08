<?php

defined('STDIN') or die('Access Denied');

use Obullo\Console\Commands\App;

/**
 * $app maintenance control
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function () use ($c) {
        $app = new App($c, func_get_args());
        $app->run();
    }    
);

/* End of file app.php */
/* Location: .app/tasks/controller/app.php */