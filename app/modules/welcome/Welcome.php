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
        // var_dump($this->request->getUri()->getPath());

        // echo $this->url->siteUrl();
        // echo $this->request->getServerParams()['REQUEST_URI'];

        $this->view->load(
            'welcome',
            [
                'title' => 'Welcome to Obullo !',
            ]
        );
    }
}