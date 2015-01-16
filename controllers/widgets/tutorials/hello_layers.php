<?php

namespace Widgets\Tutorials;

Class Hello_Layers extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('url');
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $a = $this->c['layer']->get('widgets/tutorials/hello_dummy/index/1/2/3');
        $b = $this->c['layer']->get('welcome/dummy/index/4/5/6');
        $c = $this->c['layer']->get('widgets/tutorials/hello_dummy/index/7/8/9');

        $this->c['view']->load(
            'hello_layers', 
            function () use ($a, $b, $c) {
                $this->assign('a', $a);
                $this->assign('b', $b);
                $this->assign('c', $c);
            }
        );
    }
}

/* End of file hello_layers.php */
/* Location: .controllers/tutorials/hello_layers.php */