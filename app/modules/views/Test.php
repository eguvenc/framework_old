<?php

namespace Views;

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
        echo '<pre>Request: <span class="string">'.$this->uri->getUriString().'</span></pre>';
        echo '<pre>Global Request Object: <span class="string">'.$this->app->uri->getUriString().'</span></pre>';
        echo '<p>-----------------------------------------</p>';

        echo 'VIEWS MODULE:'.$this->router->getNamespace().'<br>';
    }
}