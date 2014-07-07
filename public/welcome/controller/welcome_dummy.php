<?php

/**
 * $app hello_dummy
 * 
 * Dummy test class for testing "Layers"
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('view');
        $c->load('layer');
    }
);

$app->func(
    'index', 
    function ($arg1 = '', $arg2 = '', $arg3 = '') {
        
        echo '<pre>Request: <span class="string">'.$this->uri->getUriString().'</span></pre>';
        echo '<pre>Response: <span class="string">'.$arg1 .' - '.$arg2. ' - '.$arg3.'</span></pre>';
        echo '<pre>Global Request Object: <span class="string">'.$this->request->globals('uri')->getUriString().'</span></pre>';
        echo '<p>-----------------------------------------</p>';

        // var_dump($this->router->fetchDirectory());
        
        echo $this->layer->get('views/header');
        
        echo $this->view->layer($this)->load('dummy', false);
     
    }
);

/* End of file welcome_dummy.php */
/* Location: .public/tutorials/controller/welcome_dummy.php */