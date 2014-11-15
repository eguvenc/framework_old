<?php

/**
 * $app login
 * 
 * @var Controller
 */ 
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('service/user');
    }
);

$app->func(
    'index',
    function () use ($c) {

        $this->user->identity->logout();
        // $this->user->identity->destroy();
        // $this->user->identity->forgetMe();
        $this->url->redirect('/examples/login');
    }
);

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */