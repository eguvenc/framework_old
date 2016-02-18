<?php

namespace Examples\Debugger;

use RuntimeException;
use Obullo\Http\Controller;

class Debugger extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if (! is_file(FOLDERS .'Debugger.php')) {
            throw new RuntimeException(
                "Debugger controller is not exists. Please first install it."
            );
        }
        $this->view->load('debugger');
    }
}