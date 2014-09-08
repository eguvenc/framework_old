<?php

defined('STDIN') or die('Access Denied');

use Obullo\Console\Commands\Help;

/**
 * $app help
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function () use ($c) {
        $help = new Help($c);
        $help->run();
    }
);

/* End of file help.php */
/* Location: .app/tasks/controller/help.php */