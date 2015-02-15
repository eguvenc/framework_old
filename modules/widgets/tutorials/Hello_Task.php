<?php

namespace Widgets\Tutorials;

Class Hello_Task extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['url'];
        $this->c['cli/task as task'];
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