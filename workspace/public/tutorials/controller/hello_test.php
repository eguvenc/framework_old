<?php

/**
 * $c hello_test
 * 
 * Dummy test class for Lvc
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('uri');
        $c->load('view');
        $c->load('public');
    }
);

$app->func(
    'index', 
    function () use ($c) {

        echo $this->public->get('welcome/welcome_dummy/1/2/3');
        echo $this->public->get('views/header');
        echo $this->public->get('tutorials/hello_dummy/1/2/6');

        // var_dump($this->router);
        // var_dump($this->uri);
    }
);

/* End of file hello_dummy.php */
/* Location: .public/tutorials/controller/hello_dummy.php */