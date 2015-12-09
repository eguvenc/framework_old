<?php

namespace Views;

use Obullo\Http\Controller;

class Header extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        echo $this->view->get(
            'header',
            [
                'header' => '<div class="layer example-gray">Header Controller<div class="layer example-white">$this->layer->get("views/header")</div></div>'
            ]
        );
    }
}