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
        var_dump($this->request->getQueryParams());

        echo '<pre>Request: <span class="string">'.$this->request->getUri()->getPath().'</span></pre>';
        echo '<pre>Global Request Object: <span class="string">'.$this->app->request->getUri()->getPath().'</span></pre>';
        echo '<p>-----------------------------------------</p>';

        echo 'VIEWS MODULE:'.$this->router->getNamespace().'<br>';
    }
}