<?php

use Obullo\Http\Controller;
use Obullo\Debugger\Manager;

class Debugger extends Controller
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
     * @param object $container Interop\Container\ContainerInterface
     * 
     * @return void
     */
    public function __construct($container)
    {
        $this->debugger = new Manager($container);
    }

    /**
     * Write index
     *  
     * @return string
     */
    public function index()
    {
        return $this->response->html(
            $this->debugger->printIndex(),
            200
        );
    }

    /**
     * Write body
     * 
     * @return string
     */
    public function body()
    {
        return $this->response->html(
            $this->debugger->printBody(),
            200
        );
    }

    /**
     * Server ping
     * 
     * @return int 1 or 0
     */
    public function ping()
    {
        return $this->response->html(
            (string)$this->debugger->ping(),
            200
        );
    }

    /**
     * Close server
     * 
     * @return int 1 or 0
     */
    public function disconnect()
    {
        /**
         * Disable websocket
         */
        $newArray = $this->config->load('config');

        if ($newArray['extra']['debugger'] == true) {
            $disconnect = 1;
            $newArray['extra']['debugger'] = false;
            $this->config->write('config', $newArray);
        } else {
            $disconnect = 0;
        }

        return $this->response->html(
            $disconnect,
            200
        );
    }

    /**
     * Clear all log data
     * 
     * @return voide
     */
    public function clear()
    {
        return $this->index();
    }

}