<?php

defined('STDIN') or die('Access Denied');

use Obullo\Console\Commands\Clear;

/**
 * $c clear
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function () use ($c) {
        $clear = new Clear($c);
        $clear->run();
    }
);

/* End of file clear.php */
/* Location: .app/tasks/controller/clear.php */