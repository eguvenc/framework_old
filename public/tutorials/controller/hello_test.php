<?php

/**
 * $app hello_test
 * 
 * Dummy test class for Layerd Vc
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('uri');
        $c->load('view');
        $c->load('layer');
    }
);

$app->func(
    'index', 
    function () use ($c) {

        echo $this->layer->get('welcome/welcome_dummy/1/2/3');
        echo $this->layer->get('views/header');
        echo $this->layer->get('tutorials/hello_dummy/1/2/6');

        // var_dump($this->router->fetchDirectory());
        // var_dump($this->uri);
    }
);

/* End of file hello_dummy.php */
/* Location: .public/tutorials/controller/hello_dummy.php */