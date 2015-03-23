<?php

namespace Widgets\Tutorials;

class Hello_Test extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['uri'];
        $this->c['view'];
        $this->c['layer'];
        $this->c['request'];
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        echo $this->layer->get('welcome/dummy/index/1/2/3');
        echo $this->layer->get('views/header');
        echo $this->layer->get('views/header');
        echo $this->layer->get('welcome/dummy/index/4/5/6');
        echo $this->layer->get('widgets/tutorials/hello_dummy/index/1/2/6');
        echo $this->layer->get('views/header');


    }
}


/* End of file hello_test.php */
/* Location: .controllers/tutorials/hello_test.php */