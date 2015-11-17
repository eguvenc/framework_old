<?php

namespace Widgets\Tutorials;

use Obullo\Http\Controller;

class HelloLayers extends Controller
{
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