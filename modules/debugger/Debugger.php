<?php

namespace Debugger;

use Obullo\Debugger\Manager;

class Debugger extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->debugger = new Manager($this->c);
    }

    /**
     * Write index
     *  
     * @return string
     */
    public function index()
    {
        echo $this->debugger->printIndex();
    }

    /**
     * Write body
     * 
     * @return string
     */
    public function body()
    {
        echo $this->debugger->printBody();
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
     * Server ping
     * 
     * @return int 1 or 0
     */
    public function ping()
    {
        echo $this->debugger->ping();
    }

    /**
     * Clear all log data
     * 
     * @return voide
     */
    public function clear()
    {
        $this->index();
    }

}