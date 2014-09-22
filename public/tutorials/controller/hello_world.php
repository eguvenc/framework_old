<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */ 
$app = new Controller(
    function ($c) {
        $c->load('view');
        // $c->load('service/user/login');

        // $this->logger->load(LOGGER_MONGO);

        // $this->logger->info('HELLO INFO !!!!!');
        // $this->logger->notice('HELLO NOTICE !!!!!');
        // $this->logger->alert('HELLO ALERT !!!!!');

        // $this->logger->push(LOGGER_MONGO);
        
        $c->load('db');

        $start = microtime(true);  // Run Timer     
        

        // $this->db->prepare('SELECT * FROM users WHERE username = ?');
        // $this->db->bindValue(1, 'eguvensc', PARAM_STR);
        // $this->db->execute();

        // var_dump($this->db->rowArray());

        // $t = 'asdkooooooooooooooooooooooooooooooooooooooooooooo30rj23j9j023j9rj32jr935444444444444444444444444444444444444444444444444444444444qweqweqwğş1*l';

        // // echo hash('sha256', 'asdkooooooooooooooooooooooooooooooooooooooooooooo30rj23j9j023j9rj32jr935444444444444444444444444444444444444444444444444444444444qweqweqwğş1*l');
        
        // echo hash('md5', $t);
        

        $end = microtime(true) - $start;  // End Timer

        echo '<br>'.number_format($end, 8);

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