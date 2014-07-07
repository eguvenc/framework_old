<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('view');

        // $session = 'flash:old:notice|s:34:"Form element successfully deleted.";flash:old:status|i:1;_o2_meta{"session_id":"3s08c265ao7lmk3hsqhhke1mn0","ip_address":"127.0.0.1","user_agent":"Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:30.0) Gecko/20100101 Firefox/30.0","last_activity":1404743275}';

        // $output = explode('_o2_meta', $session);
        // print_r($output[1]);

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