<?php

/**
 * Logger Output Class
 * 
 * @category  Logger
 * @package   Logger_Output
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/logger
 */
Class Logger_Output
{
    public $logger;

    /**
     * Constructor
     * 
     * @param object $logger logger object
     */
    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    // ------------------------------------------------------------------------

    /**
     * Log html output
     * 
     * @return string echo the log output
     */
    public function __toString()
    {
        global $packages;

        $isXmlHttp = ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : false;
        $output    = '';
        if ( ! $isXmlHttp AND ! defined('STDIN')) {      // disable html output for ajax and task requests
            foreach ($this->logger->getRecordArray() as $value) {
                $output.= str_replace('\n', '<br />', $value);
            }
            $template = file_get_contents(PACKAGES .'logger_output'. DS .'releases'. DS .$packages['dependencies']['logger_output']['version']. DS .'src'. DS .'html'. EXT);
            return str_replace(array('{output}','{title}'), array($output,'LOGGER OUTPUT'), $template);
        }
        return $output;
    }
}

// END Output class

/* End of file Logger.php */
/* Location: ./packages/logger/releases/0.0.1/src/output.php */
