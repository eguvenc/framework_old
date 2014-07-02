<?php

/**
 * $app hello_test layers
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('view');
        $c->load('html');
        $c->load('url');
        $c->load('request');
        $c->load('layer');
    }
);

$app->func(
    'index',
    function () {

        $a = $this->layer->get('welcome/welcome_dummy/4/5/6');
        $b = $this->layer->get('welcome/welcome_dummy/10/11/12');

        echo $a;
        echo $b;

        // $this->view->load(
        //     'hello_layers', 
        //     function () use ($a, $b, $c) {
        //         $this->assign('a', $a);
        //         $this->assign('b', $b);
        //         $this->assign('c', $c);
        //         $this->assign('footer', $this->template('footer'));
        //     }
        // );

    }
);

/* End of file hello_test_view.php */
/* Location: .public/tutorials/controller/hello_test_view.php */