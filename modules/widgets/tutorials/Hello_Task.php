<?php

namespace Widgets\Tutorials;

class Hello_Task extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['url'];
        $this->c['task'];
    }

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