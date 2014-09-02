<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */ 
$app = new Controller(
    function ($c) {
        $c->load('view');

        // $c->load('user/agent as agent');

        // // echo $this->agent->getAgent();
        // echo md5($_SERVER['HTTP_USER_AGENT']);

        // $a = 'ersin';

        // echo (int)$a;
        // 

        // echo $hostname = gethostname();

        // echo preg_filter('/[a-z]/', '', );

        // echo sprintf("%u", crc32((string)$hostname));
        // echo sprintf("%u", crc32(uniqid()));

        // echo $uniqid;
        // 
        // echo preg_filter('/[a-z]/', '', $uniqid);

        // $this->logger->load(LOGGER_MONGO);

        // $this->logger->info('HELLO INFO !!!!!');
        // $this->logger->notice('HELLO NOTICE !!!!!');
        // $this->logger->alert('HELLO ALERT !!!!!');

        // $this->logger->push(LOGGER_MONGO);
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