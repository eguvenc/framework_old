<?php

namespace Widgets\Tutorials;

use Obullo\Http\Controller;

class HelloTest extends Controller
{
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