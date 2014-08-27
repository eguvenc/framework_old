<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('view');
        $c->load('form');
        $c->load('flash/session as flash');

        $this->flash->success('Successful Success !');
        $this->flash->warning('Successful Warning !');

        echo $this->flash->output();

        // preg_match('#test.framework#', 'test.framework', $matches);
        // print_r($matches);

        // $c->load('service/provider/database as db', 'q_jobs');
        // $this->db->insert('failures', array('job_id' => 2, 'error_xdebug' => null));

        // $this->logger->load(LOGGER_EMAIL);
        // $this->logger->notice('test email notice ! ');
        // $this->logger->alert('test email alert ', array('test' => rand()));
        // $this->logger->push(LOGGER_EMAIL);
        
        // $this->logger->printDebugger();

        // $this->logger->load(LOGGER_MONGO)->filter('priority.notIn', array(LOG_DEBUG));
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