<?php

/**
 * $c hello_scheme
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('html');
        $c->load('view');
        $c->load('url');
    }
);

$app->func(
    'index',
    function () {

        $this->view->load(
            'hello_scheme',
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('title', 'Hello Scheme World !');
                $this->getScheme('welcome');
            }
        );
    }
);

/* End of file hello_scheme.php */
/* Location: .public/tutorials/controller/hello_scheme.php */