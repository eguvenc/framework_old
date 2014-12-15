<?php

Class Hello_Layers extends Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('url');
        $this->c->load('view');
        $this->c->load('request');
        $this->c->load('layer');
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $a = $this->layer->get('tutorials/hello_dummy/1/2/3');
        $b = $this->layer->get('welcome/welcome_dummy/4/5/6');
        $c = $this->layer->get('tutorials/hello_dummy/7/8/9');
    
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
}

/* End of file hello_layers.php */
/* Location: .public/tutorials/controller/hello_layers.php */