<?php

/**
 * Logging Helper
 *
 * @package     Obullo
 * @subpackage  log
 * @category    Logging
 * @author      Obullo Team.
 * @link        
 */

// --------------------------------------------------------------------

/**
 * Write Log File
 *
 * Generally this function will be called using the global log_me() function.
 *
 * @access   public
 * @param    string    the error level
 * @param    string    the error message
 * @return   bool
 */        
if( ! function_exists('log_write') ) 
{
    function log_write($level = 'error', $msg = '')
    {   
        // Convert new lines to a temp symbol, than we replace it and read for console debugs.
        $msg = trim(preg_replace('/\n/', '[@]', $msg), "\n");
        
        $threshold = 1;
        $date_fmt  = 'Y-m-d H:i:s';
        $enabled   = TRUE;
        $levels    = array('BENCH' => '0', 'ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'ALL' => '4');
        $level     = strtoupper($level);

        $config          = get_config();
        $log_path        = APP .'logs'. DS;
        $log_threshold   = $config['log_threshold'];
        $log_date_format = $config['log_date_format'];
        
        if (defined('STDIN') AND defined('TASK'))   // Internal Task Request
        {
            $log_path = rtrim($log_path, DS) . DS .'tasks' . DS;
        } 
        elseif(defined('STDIN'))  // Command Line && Task Requests
        {
            if(isset($_SERVER['argv'][1]) AND $_SERVER['argv'][1] == 'clear') //  Do not keep clear command logs.
            {
                return FALSE;
            }
            
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
            $message .= "<"."?php defined('BASE') or die('Access Denied') ?".">\n\n";
        }

        $message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($date_fmt). ' --> '.$msg."\n";  
    
        if ( ! $fp = @fopen($filepath, 'ab'))
        {
            return FALSE;
        }
    
        flock($fp, LOCK_EX);    
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);

        @chmod($filepath, '0666');
                 
        return TRUE;
    }
}

/* End of file log.php */
/* Location: ./ob_modules/log/releases/0.0.1/log.php */