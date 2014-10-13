<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */ 
$app = new Controller(
    function ($c) {
        $c->load('view');
        // $c->load('service/queue');

        // // $this->logger->alert('This is an alert message !');

        // $this->queue->channel('Test');
        // $this->queue->push('Workers\QueueTest', 'Server1.logger', array('log' => array('debug' => 'Test')));

        // $end = microtime(true) - $start;  // End Timer
        // echo '<br>'.number_format($end, 12);


        // $end = microtime(true) - $start;  // End Timer

        // echo '<br>'.number_format($end, 4);

    }
);

$app->func(
    'index',
    function () {

        $this->view->load(
            'hello_world',
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('footer', $this->template('footer'));
            }
        );

    }
);

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */