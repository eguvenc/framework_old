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
        $this->c->load('url');
        $this->c->load('cli/task as task');
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

/* End of file hello_task.php */
/* Location: .controllers/tutorials/hello_task.php */