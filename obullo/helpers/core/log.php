<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @filesource
 * @license
 */

/**
 * Logging Helper
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Obullo Team.
 * @link        
 */

// --------------------------------------------------------------------

/**
* Error Logging Interface
*
* We use this as a simple mechanism to access the logging
* class and send messages to be logged.
*
* @access    public
* @return    void
*/
if( ! function_exists('log_me') ) 
{
    function log_me($level = 'error', $message = '', $php_error = FALSE, $core_level = FALSE)
    {    
        if (config('log_threshold') == 0)
        {
            return;
        }

        if ($core_level == FALSE)  // Router and URI classes are core level class
        {                          // so they must be write logs to application log folder,
                                   // otherwise log functionality not works.

            $router = lib('ob/Router');  // If current module /logs dir exists
            $uri    = lib('ob/Uri');     // write module logs into current module.

            if (is_object($router) AND is_object($uri))
            {   
                $config = lib('ob/Config');

                if ($config->item('log_threshold') == 0)
                {
                    return;
                }
            }
        }

        log_write($level, $message, $php_error);

        return;
    }
}

// --------------------------------------------------------------------

/**
 * Write Log File
 *
 * Generally this function will be called using the global log_me() function.
 * Do not use Object in this function.
 *
 * @access   public
 * @param    string    the error level
 * @param    string    the error message
 * @param    bool      whether the error is a native PHP error
 * @param    bool      if true we use log function for current module
 * @return   bool
 */        
if( ! function_exists('log_write') ) 
{
    function log_write($level = 'error', $msg = '', $php_error = FALSE)
    {   
        // Convert new lines to a temp symbol, than we replace it and read for console debugs.
        $msg = trim(preg_replace('/\n/', '[@]', $msg), "\n");
        
        // @todo php errors.
        $php_error = NULL;
        
        $log_path  = '';
        $threshold = 1;
        $date_fmt  = 'Y-m-d H:i:s';
        $enabled   = TRUE;
        $levels    = array('ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'ALL' => '4');
        $level     = strtoupper($level);

        $config          = get_config();
        $log_path        = ($config['log_path'] != '') ? $config['log_path'] : APP .'core'. DS .'logs'. DS;
        $log_threshold   = $config['log_threshold'];
        $log_date_format = $config['log_date_format'];
        
        if (defined('CMD') AND defined('TASK'))   // Internal Task Request
        {
            $log_path = rtrim($log_path, DS) . DS .'tasks' . DS;
        } 
        elseif(defined('CMD'))  // Command Line Task Request
        {
            $log_path = rtrim($log_path, DS) . DS .'cmd' . DS; 
        }         
        
        if ( ! is_dir(rtrim($log_path, DS)) OR ! is_really_writable($log_path))
        {
            $enabled = FALSE;
        }

        if (is_numeric($log_threshold))
        {
            $threshold = $log_threshold;
        }
            
        if ($log_date_format != '')
        {
            $date_fmt = $log_date_format;
        }
        
        if ($enabled === FALSE)
        {
            return FALSE;
        }
        
        if ( ! isset($levels[$level]) OR ($levels[$level] > $threshold))
        {
            return FALSE;
        }

        $filepath = $log_path .'log-'. date('Y-m-d').EXT;
        $message  = '';  
        
        if ( ! file_exists($filepath))
        {
            $message .= "<"."?php defined('BASE') or exit('Access Denied!'); ?".">\n\n";
        }

        $message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($date_fmt). ' --> '.$msg."\n";  
    
        if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE))
        {
            return FALSE;
        }
    
        flock($fp, LOCK_EX);    
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);

        @chmod($filepath, FILE_WRITE_MODE);
                 
        return TRUE;
    }
}

/* End of file log.php */
/* Location: ./obullo/helpers/core/log.php */