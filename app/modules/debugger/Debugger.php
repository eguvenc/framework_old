<?php

namespace Debugger;

use Obullo\Debugger\Manager;

class Debugger extends \Controller
{
    /**
     * Manager
     * 
     * @var object
     */
    protected $debugger;

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        $this->debugger = new Manager;
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