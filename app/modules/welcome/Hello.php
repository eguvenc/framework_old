<?php

namespace Welcome;

use Obullo\Http\Controller;
use View\Base;

class Hello extends Controller
{
    use Base;

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        echo "Hello";
    }
}