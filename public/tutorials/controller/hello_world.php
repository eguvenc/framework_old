<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('view');

        // echo $a;
        // $c->load('translator');
        // $c->load('session/flash as flash');
        
        // $c->load('service/provider/mongo as mongo');

        // $this->logger->load(LOGGER_MONGO);

        // echo $a;

        // // $this->flash->success('testss');

        // echo $this->flash->get('notice:success');

        // var_dump($this->mongo);

        // $id = uniqid();
        // $c->load('service/queue');
        // $this->queue->channel('ObulloLog');
        // $this->queue->push('Server1.logger', 'SendLog', array('log' => array('debug' => 'test')));
        // $this->queue->push('Logger', 'SendLog', array('id' => $id, 'message' => 'this is my message'));

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