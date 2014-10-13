<?php

defined('STDIN') or die('Access Denied');

/**
 * $app queue worker
 * 
 * @var Controller
 */
$app = new Controller(
    function () use ($c) {
        $c->load('queue/worker as worker');
    }    
);

$app->func(
    'index',
    function ($channel = null, $route = null, $memory = 128, $delay = 0, $timeout = 0, $sleep = 0, $maxTries = 0, $debug = 0, $env = 'prod') {
        $this->worker->init($channel, $route, $memory, $delay, $timeout, $sleep, $maxTries, $debug, $env = 'prod');
        $this->worker->pop();
    }
);

/* End of file worker.php */
/* Location: .app/tasks/controller/worker.php */