<?php

namespace Examples\Layers;

use Obullo\Http\Controller;

class Test extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $a = $this->layer->get('examples/layers/dummy/index/1/2/3');
        $b = $this->layer->get('welcome/dummy/index/4/5/6');
        $c = $this->layer->get('examples/layers/dummy/index/7/8/9');

        $this->view->load(
            'test', 
            [
                'a' => $a,
                'b' => $b,
                'c' => $c,
            ]
        );
    }
}