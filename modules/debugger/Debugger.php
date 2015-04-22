<?php

namespace Debugger;

use Obullo\Debugger\DebugManager;

class Debugger extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        if ($this->c['app']->env() == 'production') {  // Disable debugger in production mode
            $this->c['response']->show404();
        }
        $this->debugger = new DebugManager($this->c);
    }

    /**
     * Write iframe
     *  
     * @return void
     */
    public function index()
    {
        echo $this->debugger->indexHtml();
    }

    /**
     * Write console output
     * 
     * @return void
     */
    public function console()
    {
        echo $this->debugger->printConsole();
    }

    /**
     * Close debugger window
     * 
     * @return void
     */
    public function off()
    {
        echo $this->debugger->off();
    }

    /**
     * Clear all log data
     * 
     * @return voide
     */
    public function clear()
    {
        $this->debugger->clear();
        echo $this->debugger->printConsole();
    }

}