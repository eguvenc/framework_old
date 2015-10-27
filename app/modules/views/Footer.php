<?php

namespace Views;

use Obullo\Http\Controller;

/**
 * View controller
 */
class Footer extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        echo $this->view->get(
            'footer',
            [
                'footer' => '<pre>--------------- EXAMPLE FOOTER LAYER ---------------</pre>'
            ]
        );
    }
}