<?php

defined('STDIN') or die('Access Denied');

/**
 * $app queue listener
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function () use ($c) {
        $c->load('queue/listener', func_get_args());
    }
);


/* End of file help.php */
/* Location: .app/tasks/controller/queue.php */