<?php

/**
 * $app hello_task
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('console/task as task');
    }
);

$app->func(
    'index',
    function () {
        $output = $this->task->run('help/index', true); 
        echo '<pre>'.$output.'</pre>';
    }
);


/* End of file hello_task.php */
/* Location: .public/tutorials/controller/hello_task.php */