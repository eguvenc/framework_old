<?php

namespace Welcome;

class Hello extends \Controller
{
    use \View\Base;

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