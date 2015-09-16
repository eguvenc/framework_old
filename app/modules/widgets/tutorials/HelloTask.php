<?php

namespace Widgets\Tutorials;

class HelloTask extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $output = $this->task->run('help/index', true); 
        echo '<pre>'.$output.'</pre>';
    }
}