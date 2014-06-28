<?php

/**
 * $c hello_lvc
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('html');
        $c->load('view');
        $c->load('url');
        $c->load('request');
        $c->load('public');
    }
);

$app->func(
    'index',
    function () {

        $a = $this->public->get('tutorials/hello_dummy/1/2/3');
        $b = $this->public->get('welcome/welcome_dummy/4/5/6');
        $c = $this->public->get('tutorials/hello_dummy/7/8/9');
    
        $this->view->load(
            'hello_layers', 
            function () use ($a, $b, $c) {
                $this->assign('a', $a);
                $this->assign('b', $b);
                $this->assign('c', $c);
                $this->assign('footer', $this->template('footer'));
            }
        );

    }
);

/* End of file hello_lvc.php */
/* Location: .public/tutorials/controller/hello_lvc.php */