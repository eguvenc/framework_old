<?php

/**
 * Logger Class
 * 
 * @category  Logger
 * @package   Logger
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/logger
 */
Class Logger
{
    public $output        = false;    // Write all outputs to end of the page
    public $enabled       = true;
    public $batch         = false;
    public $threshold     = array();
    public $handler       = 'file';   // current log handler name
    public $queries       = false;    // log sql queries
    public $benchmark     = false;    // log bechmark data, memory usage etc.
    public $line          = '';       // line output format
    public $push_handlers = array();
    public $severities    = array();
    public $channel       = 'system'; // current dynamic channel
    public $record        = array();  // formatted current log data
    public $recordArray   = array();  // formatted all log data for batch process
    protected $push_data  = array();

    // ------------------------------------------------------------------------

    /**
    * Constructor
    */
    public function __construct()
    {
        global $packages, $config;
        
        $this->output          = $config['log_output'];
        $this->channel         = $config['log_default_channel'];
        $this->threshold       = $config['log_threshold'];
        $this->handler         = key($config['log_handler']);
        $this->queries         = $config['log_queries'];
        $this->benchmark       = $config['log_benchmark'];
        $this->line            = $config['log_line'];
        $this->push_handlers   = $config['log_push_handlers'];
        $this->severities  = array(
           'emergency' => 0,
           'alert'     => 1,
           'critical'  => 2,
           'error'     => 3,
           'warning'   => 4,
           'notice'    => 5,
           'info'      => 6,
           'debug'     => 7,
            );
        $driver = strtolower($config['log_handler'][$this->handler]);
        include PACKAGES .$driver. DS .'releases'. DS .$packages['dependencies'][$driver]['version']. DS .$driver. EXT;

        $this->driver = new $config['log_handler'][$this->handler]($this);
    }

    // --------------------------------------------------------------------

    /**
     * Get property of the logger
     * 
     * @param string $key property of logger
     * 
     * @return mixed
     */
    public function getProperty($key)
    {
        return $this->{$key};
    }

    // --------------------------------------------------------------------

    /**
     * Set property to the logger class
     * 
     * @param string $key property to logger
     * @param mixed  $val value of property
     * 
     * @return void
     */
    public function setProperty($key, $val)
    {
        $this->{$key} = $val;
    }

    // --------------------------------------------------------------------

    /**
     * Add or change channel
     * 
     * @param string $channel add a channel
     * 
     * @return void
     */
    public function channel($channel)
    {
        $this->setProperty('channel', $channel);
    }

    // --------------------------------------------------------------------
    
    /**
     * Push to another handler
     * 
     * $logger->channel('security');
     * $logger->alert('Possible hacking attempt !', array('username' => $username));
     * $logger->push('email');  // send log data using email handler
     * $logger->push('mongo');  // send log data to mongo db
     * 
     * @param string $handler set channel handler
     * 
     * @return void
     */
    public function push($handler = 'email')
    {
        return; // this feature disabled for now. we will improve it.

        if ( ! isset($this->record['level']) OR ! $this->isAllowed($this->record['level'])) {  // check allowed
            return;
        }

        $handler_class = $this->push_handlers[$handler];
        $driver        = strtolower($handler_class);

        global $packages;

        if ( ! class_exists($handler)) {
            include PACKAGES .$driver. DS .'releases'. DS .$packages['dependencies'][$driver]['version']. DS .$driver. EXT;
        }
        $this->push_data[$handler] = new $handler;

        if ( ! $this->batch) {                          // If not batch
            $this->push_data[$handler]->format($this->getRecord());  // send log data to format
            $this->push_data[$handler]->write();                     // write log data using current handler
        }
    }

    // --------------------------------------------------------------------

    /**
     * Reset defaults.
     * 
     * @return void
     */
    public function clear()
    {
        global $config;
        $this->channel = $config['log_default_channel'];
    }

    // --------------------------------------------------------------------

    /**
     * Call logger file methods
     * 
     * @param string $method    methodname
     * @param array  $arguments arguments
     * 
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        $record_unformatted = array();
        if ($this->isAllowed($method)) {
            $record_unformatted['level']   = $method;
            $record_unformatted['message'] = $arguments[0];
            $record_unformatted['context'] = (isset($arguments[1])) ? $arguments[1] : array();

            $this->record = $this->driver->format($record_unformatted);  // create log data

            if ($this->batch) {
                $this->recordArray[] = $this->record;   // store to array instead of write
                $this->clear();             // reset channel data
            } else {
                $this->driver->write();
                $this->clear();             // reset channel data
            }
        }
    }

    // --------------------------------------------------------------------
    
    /**
     * Is it allowed level ?
     *
     * @param string $level current level ( debug, alert .. )
     * 
     * @return boolean 
     */
    public function isAllowed($level)
    {
        if (isset($this->severities[$level]) AND in_array($this->severities[$level], $this->threshold)) {
            return true;
        }
        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Get one record data
     * 
     * @return string current line of record
     */
    public function getRecord() 
    {
        return $this->record;
    }

    // --------------------------------------------------------------------

    /**
     * Get all batched records
     * 
     * @return array batch data
     */
    public function getRecordArray() 
    {
        return $this->recordArray;
    }

    // --------------------------------------------------------------------

    /**
     * End of the logs, start batch process
     */
    public function __destruct()
    {
        if ($this->enabled == false) {
            return;
        }
        if ($this->output) {

            $output = new Logger_Output($this);
            echo $output;
        }
        $this->driver->write();  // start default driver batch process

        if ($this->batch) {  // start batch for push handlers
            foreach ($this->push_data as $handler) {
                $handler->write();
            }
        }
    }

}

// END Logger class

/* End of file Logger.php */
/* Location: ./packages/logger/releases/0.0.1/logger.php */
