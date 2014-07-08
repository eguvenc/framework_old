<?php

/**
 * $app hello_test
 * 
 * Dummy test class for Layers
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('uri');
        $c->load('view');
        $c->load('layer');
        $c->load('request');
    }
);

$app->func(
    'index', 
    function () use ($c) {

        echo $this->layer->get('views/header');
        echo $this->layer->get('welcome/welcome_dummy/1/2/3');
        echo $this->layer->get('views/header');
        echo $this->layer->get('welcome/welcome_dummy/1/2/3');
        echo $this->layer->get('tutorials/hello_dummy/1/2/6');
        echo $this->layer->get('views/header');

        $this->view->load(
            'hello_world',
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('footer', $this->template('footer'));
            }
        );

    }
);

/* End of file hello_dummy.php */
/* Location: .public/tutorials/controller/hello_dummy.php */