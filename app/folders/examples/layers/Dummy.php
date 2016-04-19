<?php

namespace Examples\Layers;

use Obullo\Http\Controller;

class Dummy extends Controller
{
    /**
     * Index
     * 
     * @param string $arg1 test argument1
     * @param string $arg2 test argument2
     * @param string $arg3 test argument3
     * 
     * @return void
     */
    public function index($arg1 = '', $arg2 = '', $arg3 = '')
    {
        echo '<pre>Request: <span class="string">'.$this->request->getUri()->getPath().'</span></pre>';
        echo '<pre>Response: <span class="string">'.$arg1 .' - '.$arg2. ' - '.$arg3.'</span></pre>';
        echo '<pre>Global Request Object: <span class="string">'.$this->app->request->getUri()->getPath().'</span></pre>';
        echo '<p></p>';
    }
}