<?php

/**
 * $app hello_dummy
 * 
 * Dummy test class for Lvc
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('uri');
    }
);

$app->func(
    'index', 
    function ($arg1 = '', $arg2 = '', $arg3 = '') {

        echo '<pre>Request: <span class="string">'.$this->uri->getUriString().'</span></pre>';
        echo '<pre>Response: <span class="string">'.$arg1 .' - '.$arg2. ' - '.$arg3.'</span></pre>';
        echo '<pre>Global Request Object: <span class="string">'.$this->request->globals('uri')->getUriString().'</span></pre>';
        echo '<p>-----------------------------------------</p>';

        echo $this->layer->get('views/header');
        echo $this->layer->get('views/header');
        echo $this->layer->get('views/header');
        
        var_dump($this->router->fetchDirectory());
        // echo $this->view->load('dummy', false)->layer('welcome');
        // exit;
        
    }
);

/* End of file hello_dummy.php */
/* Location: .public/tutorials/controller/hello_dummy.php */