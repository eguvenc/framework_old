<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('view');

        $this->logger->load(LOGGER_EMAIL);
        $this->logger->notice('test email notice ! ');
        $this->logger->alert('test email alert ', array('test' => 'example data 123'));
        $this->logger->push(LOGGER_EMAIL);

        // $this->logger->load(LOGGER_MONGO);
        // $this->logger->info('HELLO INFO !!!!!');
        // $this->logger->notice('HELLO NOTICE !!!!!');
        // $this->logger->alert('HELLO ALERT !!!!!');
        // $this->logger->push(LOGGER_MONGO);

        // $c->load('service/queue');
        // $this->queue->channel('Logs');
        // $this->queue->push('SendLog', 'Server1.Logger.File', array('log' => array('debug' => 'Test')));
        // $this->queue->push('SendLog', 'Server1.Logger.Email', array('message' => 'This is my message'));
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