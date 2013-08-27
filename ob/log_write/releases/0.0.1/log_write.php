<?php

/**
 * Log Write Class
 *
 *
 * @package       Obullo
 * @subpackage    log_write
 * @category      logging
 * @author        Obullo Team
 * @link          
 */

Class Log_Write {

    // --------------------------------------------------------------------
    
    /**
    * Write Log File
    *
    * This function will be called using the global log\me() function.
    *
    * @access   public
    * @param    string    the error level
    * @param    string    the error message
    * @return   bool
    */
    function write($level = 'error', $msg = '')
    {   
        // Convert new lines to a temp symbol, than we replace it and read for console debugs.
        $msg = trim(preg_replace('/\n/', '[@]', $msg), "\n");

        $threshold = 1;
        $date_fmt  = 'Y-m-d H:i:s';
        $enabled   = true;
        $levels    = array('ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'BENCH' => '4', 'ALL' => '5');
        $level     = strtoupper($level);

        $log_path        = APP .'logs'. DS;
        $log_threshold   = config('log_threshold');
        $log_date_format = config('log_date_format');

        if (defined('STDIN') AND defined('TASK'))   // Internal Task Request
        {
            $log_path = rtrim($log_path, DS) . DS .'tasks' . DS;
        } 
        elseif(defined('STDIN'))  // Command Line && Task Requests
        {
            if(isset($_SERVER['argv'][1]) AND $_SERVER['argv'][1] == 'clear') //  Do not keep clear command logs.
            {
                return false;
            }

            $log_path = rtrim($log_path, DS) . DS .'cmd' . DS; 
        }         

        if ( ! is_dir(rtrim($log_path, DS)) OR ! isReallyWritable($log_path))
        {
            $enabled = false;
        }

        if (is_numeric($log_threshold))
        {
            $threshold = $log_threshold;
        }

        if ($log_date_format != '')
        {
            $date_fmt = $log_date_format;
        }

        if ($enabled === false)
        {
            return false;
        }

        if ( ! isset($levels[$level]) OR ($levels[$level] > $threshold))
        {
            return false;
        }

        $filepath = $log_path .'log-'. date('Y-m-d').EXT;
        $message  = '';  

        if ( ! file_exists($filepath))
        {
            $message .= "<"."?php defined('ROOT') or die('Access Denied') ?".">\n\n";
        }

        $message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($date_fmt). ' --> '.$msg."\n";  

        if ( ! $fp = @fopen($filepath, 'ab'))
        {
            return false;
        }

        flock($fp, LOCK_EX);    
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);

        @chmod($filepath, '0666');

        return true;
    }    
    
}

// END log_write class

/* End of file Log_Write.php */
/* Location: ./ob/email/releases/0.0.1/log_write.php */