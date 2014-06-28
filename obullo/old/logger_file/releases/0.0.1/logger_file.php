<?php

/**
 * Logger File Class
 * 
 * @category  Logger_File
 * @package   Logger
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/logger_file
 */
Class Logger_File 
{
    public $path;       // current log path for file driver
    public $config;     // logger file configuration
    public $logger;     // logger object

    /**
     * Constructor
     * 
     * @param object $log object of logger
     */
    public function __construct(Logger $log)
    {
        $logger = null;
        include APP .'config'. DS . strtolower(ENV) . DS .'logger'. EXT;
        
        $this->config = $logger;
        $this->logger = $log;

        $this->logger->setProperty('batch', $this->config['batch']);  // set batch switch

        // Replace data path
        //--------------------------------------
        $this->path = $this->replacePath($this->config['path']);

        // Seperate Cli & TASK requests
        //--------------------------------------
        if (defined('STDIN') AND defined('TASK')) {     // Task Requests
            $this->path = $this->replacePath($this->config['path_cli']);
        } elseif (defined('STDIN')) {                   // Cli Request
            if (isset($_SERVER['argv'][1]) AND $_SERVER['argv'][1] == 'clear') {   //  Do not keep clear command logs.
                $this->logger->setProperty('enabled', false);
            }
            $this->path = $this->replacePath($this->config['path_task']);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Convert friendly path to
     * Obullo format if you keep log data
     * in framework /data/logs folder.
     * 
     * @param string $path log path
     * 
     * @return string       current path
     */
    public function replacePath($path)
    {
        if (strpos($path, 'data') === 0) {
            $path = str_replace('/', DS, trim($path, '/'));
            $path = DATA .substr($path, 5);
        }
        return $path;
    }

    // ------------------------------------------------------------------------

    /**
    * Format log records and build lines
    *
    * This function will be called using the global $logger class methods
    *
    * @param array $unformatted log data
    * 
    * @return array formatted record
    */
    public function format($unformatted)
    {
        $record = array(          // Build record data !
            'datetime' => '',
            'channel'  => $this->logger->getProperty('channel'),
            'level'    => $unformatted['level'],
            'message'  => $unformatted['message'],
            'context'  => $unformatted['context'],
            'extra'    => (isset($unformatted['context']['extra'])) ? $unformatted['context']['extra'] : '',
        );

        $format    = $this->config['extend']['format']; //load format function
        $formatted = $format($record);

        return $this->lineFormat($formatted); // formatted record
    }

    // ------------------------------------------------------------------------

    /**
     * Format the line which is defined in app/config/$env/config.php
     * This feature just for line based loggers.
     * 
     * 'log_line' => '[%datetime%] %channel%.%level%: --> %message% %context% %extra%\n',
     * 
     * @param array $record array of log data
     * 
     * @return string returns to formated string
     */
    public function lineFormat($record)
    {
        return str_replace(
            array(
            '%datetime%',
            '%channel%',
            '%level%',
            '%message%',
            '%context%',
            '%extra%',
            ), array(
            $record['datetime'],
            $record['channel'],
            $record['level'],
            $record['message'],
            $record['context'],
            $record['extra'],
            ),
            str_replace('\n', "\n", $this->logger->getProperty('line'))
        );
    }

    // --------------------------------------------------------------------

    /**
     * Write logs to file
     * 
     * @return boolean
     */
    public function write()
    {
        $write = $this->config['extend']['write'];  // Get write function

        if ($this->logger->getProperty('batch')) {  // If batch process enabled
            $lines = '';
            foreach ($this->logger->getRecordArray() as $record) {
                 $lines.= $record;
            }
            return $write($this->path, $lines);  // run it
        }
        return $write($this->path, $this->logger->getRecord());  // run it
    }

}

// END logger_file class

/* End of file Logger_File.php */
/* Location: ./packages/logger_file/releases/0.0.1/logger_file.php */
