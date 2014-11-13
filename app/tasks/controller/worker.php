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
    function () {
        $this->worker->init(func_get_args());
        $this->worker->pop();
    }
);

/* End of file worker.php */
/* Location: .app/tasks/controller/worker.php */