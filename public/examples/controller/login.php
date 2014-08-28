<?php

/**
 * $app login
 * 
 * @var Controller
 */ 
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('html');
        $c->load('form');
        $c->load('view');
        $c->load('post');
    }
);

$app->func(
    'index',
    function () use ($c) {

        if ($this->post['dopost']) {

            $c->load('validator'); // load validator

            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('password', 'Password', 'required|min(6)');

            print_r($_POST);

            if ($this->validator->isValid()) {
                echo 'OK !';
            }
        }

        $this->view->load(
            'login',
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('footer', $this->template('footer'));
            }
        );

    }
);

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */