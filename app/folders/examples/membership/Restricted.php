<?php

namespace Examples\Membership;

use Obullo\Http\Controller;

class Restricted extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load('restricted');
    }
}