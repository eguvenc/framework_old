<?php

namespace Welcome;

class Welcome extends \Controller
{
    use \View\Layout\Base;

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