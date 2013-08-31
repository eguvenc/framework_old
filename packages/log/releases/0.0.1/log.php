<?php
namespace log {
    
    /**
    * Log Helper
    *
    * @package       packages
    * @subpackage    log
    * @category      log
    * @uses          component => log_write
    * @link        
    */
    
    Class start
    { 
        function __construct()
        {
            me('debug', 'Log Helper Initialized.');
        }
    }
    
    // --------------------------------------------------------------------

    /**
    * Error and Debug Logging
    *
    * We use this as a simple mechanism to access the logging
    * functions and send messages to be logged.
    *
    * @access    public
    * @return    void
    */
    function me($level = 'error', $message = '')
    {    
        if (config('log_threshold') == 0)
        {
            return;
        }
       
        $class = '\\'.getComponent('log');
        $logc = new $class();
        $logc->write($level, $message);
    }
}

/* End of file log.php */
/* Location: ./packages/log/releases/0.0.1/log.php */