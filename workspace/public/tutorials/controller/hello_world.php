<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('view');

        // var_dump($this->mongo);

        // $id = uniqid();
        // $c->load('service/queue');
        // $this->queue->exchange('ObulloLog');
        // $this->queue->push('SendLog', array('id' => $id, 'log' => array('debug' => 'test')), $routingKey = 'Server1.logger');
        // $this->queue->push('SendLog', array('id' => $id, 'message' => 'this is my message'), $routingKey = 'Logger');
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