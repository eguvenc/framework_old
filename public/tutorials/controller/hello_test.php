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

        echo $this->layer->get('welcome/welcome_dummy/1/2/3');
        echo $this->layer->get('welcome/welcome_dummy/1/2/3');
        echo $this->layer->get('views/header');
        echo $this->layer->get('tutorials/hello_dummy/1/2/6');
        echo $this->layer->get('views/header');


        if ( ! isset($_SERVER['LAYER_REQUEST'])) {
            echo '-------------------------';
        }

        var_dump($this->request->globals('router')->fetchDirectory());

        var_dump($this->router->fetchDirectory());


        // echo $this->layer->get('welcome/welcome_dummy/1/2/3');

        // echo $this->layer->get('tutorials/hello_dummy/1/2/6');

        // // var_dump($this->uri);
        
        // $r = $this->layer->post('ajax/user/get_departments');
        // $this->layer->post('ajax/user/get_departments');

        // echo $this->layer->get('welcome/welcome_dummy/4/2/3');
        // $this->layer->get('welcome/welcome_dummy/1/2/3');

        // var_dump($this->uri->getUriString());
        // var_dump($this->request->globals()->uri->getUriString());
        

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