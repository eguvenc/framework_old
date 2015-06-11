<?php

namespace Widgets\Tutorials;

class HelloLayers extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['url'];
        $this->c['view'];
        $this->c['layer'];
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $a = $this->layer->get('widgets/tutorials/helloDummy/index/1/2/3');
        $b = $this->layer->get('welcome/dummy/index/4/5/6');
        $c = $this->layer->get('widgets/tutorials/helloDummy/index/7/8/9');

        $this->view->load(
            'hello_layers', 
            [
                'a' => $a,
                'b' => $b,
                'c' => $c,
            ]
        );
    }
}