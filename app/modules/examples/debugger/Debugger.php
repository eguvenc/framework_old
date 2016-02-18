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
        if (! is_dir(MODULES .'debugger')) {
            throw new RuntimeException(
                "Debugger module is not enabled. Install debugger module."
            );
        }
        $this->view->load('debugger');
    }
}