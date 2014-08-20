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
        /**
         * php task queue listen --channel=Logger --route=Server1.Logger.File --memory=128 --delay=0 --timeout=3 --sleep=0 --maxTries=0 --debug=0 --env=prod
         */
        $c->load('queue/listener', func_get_args());
    }
);


/* End of file help.php */
/* Location: .app/tasks/controller/queue.php */