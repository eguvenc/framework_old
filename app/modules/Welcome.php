<?php

use Obullo\Http\Controller;

class Welcome extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load(
            'welcome',
            [
                'title' => 'Welcome to Obullo !',
            ]
        );
    }
}