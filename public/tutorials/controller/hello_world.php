<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */ 
$app = new Controller(
    function ($c) {
        $c->load('view');

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