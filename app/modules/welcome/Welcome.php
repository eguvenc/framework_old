<?php

namespace Welcome;

use Obullo\Http\Controller;
use View\Base;

class Welcome extends Controller
{
    use Base;

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