<?php

/**
 * Output Class
 *
 * Responsible for sending final output to browser  
 *
 * @package       Obullo
 * @subpackage    output
 * @category      output
 * @author        Obullo Team
 * @version       0.1
 */

Class Output {

    public $final_output;
    public $cache_expiration    = 0;
    public $headers             = array();
    public $parse_exec_vars     = true;    // whether or not to parse variables like {elapsed_time} and {memory_usage}
    
    public static $instance;
    
    public function __construct()
    {
        log\me('debug', "Output Class Initialized");
    }
    
    // --------------------------------------------------------------------
    
    public static function getInstance()
    {
       if( ! self::$instance instanceof self)
       {
           self::$instance = new self();
       } 
       
       return self::$instance;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get Output
    *
    * Returns the current output string
    *
    * @access    public
    * @return    string
    */    
    public function getOutput()
    {
        return $this->final_output;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Set Output
    *
    * Sets the output string
    *
    * @access    public
    * @param     string
    * @return    void
    */    
    public function setOutput($output)
    {
        $this->final_output = $output;
    }

    // --------------------------------------------------------------------

    /**
    * Append Output
    *
    * Appends data onto the output string
    *
    * @access    public
    * @param     string
    * @return    void
    */    
    public function appendOutput($output)
    {
        if ($this->final_output == '')
        {
            $this->final_output = $output;
        }
        else
        {
            $this->final_output.= $output;
        }
    }

    // --------------------------------------------------------------------

    /**
    * Set Header
    *
    * Lets you set a server header which will be outputted with the final display.
    *
    * Note:  If a file is cached, headers will not be sent.  We need to figure out
    * how to permit header data to be saved with the cache data...
    *
    * @access    public
    * @param    string
    * @return    void
    */    
    public function setHeader($header, $replace = true)
    {
        $this->headers[] = array($header, $replace);
    }

    // --------------------------------------------------------------------
    
    /**
    * Set HTTP Status Header
    * moved to Common procedural functions.
    * 
    * @access   public
    * @param    int     the status code
    * @param    string    
    * @return   void
    */    
    public function setStatusHeader($code = 200, $text = '')
    {
        setStatusHeader($code, $text);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Set Cache
    *
    * @access   public
    * @param    integer
    * @return   void
    */    
    public function cache($time)
    {
        $this->cache_expiration = ( ! is_numeric($time)) ? 0 : $time;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Display Output
     *
     * All "view" data is automatically put into this variable by the controller class:
     *
     * $this->final_output
     *
     * This function sends the finalized output data to the browser along
     * with any server headers and profile data.  It also stops the
     * benchmark timer so the page rendering speed and memory usage can be shown.
     *
     * @access    public
     * @return    mixed
     */        
    public function _display($output = '')
    {    
        // Set the output data
        if ($output == '')
        {
            $output =& $this->final_output;
        }
        
        // --------------------------------------------------------------------
        
        // Do we need to write a cache file?
        if ($this->cache_expiration > 0)
        {
            $this->_writeCache($output);
        }
        
        // --------------------------------------------------------------------

        // Parse out the elapsed time and memory usage,
        // then swap the pseudo-variables with the data
        
        $elapsed = bench\elapsedTime('total_execution_time_start', 'total_execution_time_end');        
        $output  = str_replace('{elapsed_time}', $elapsed, $output);
                
        if ($this->parse_exec_vars === true)
        {
            $memory = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB';
            $output = str_replace('{elapsed_time}', $elapsed, $output);
            $output = str_replace('{memory_usage}', $memory, $output);
        }       

        // Is compression requested?  
        // --------------------------------------------------------------------
        
        if (config('compress_output') === true)
        {
            if (extension_loaded('zlib'))
            {             
                if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)
                {   
                    ob_start('ob_gzhandler');
                }
            }
        }

        // Are there any server headers to send ?
        // --------------------------------------------------------------------
        if( ! headers_sent()) 
        {       
            if (count($this->headers) > 0)
            {
                foreach ($this->headers as $header)
                {
                    @header($header[0], $header[1]);
                }
            }        
        }
        
        // --------------------------------------------------------------------
        
        // Does the getInstance() function exist?
        // If not we know we are dealing with a cache file so we'll
        // simply echo out the data and exit.
        if ( ! function_exists('getInstance'))
        {
            echo $output;
            
            log\me('debug', "Final output sent to browser");
            
            if (config('log_benchmark') == true)
            {
                log\me('bench', "Total execution time: ".$elapsed);
            }
            
            return true;
        }
        
        // Does the controller contain a function named _output()?
        // If so send the output there.  Otherwise, echo it.
        $ob = getInstance();
        
        if (method_exists($ob, '_output'))
        {
            $ob->_output($output);
        }
        else
        {
            echo $output;  // Send it to the browser!
        }
        
        log\me('debug', "Final output sent to browser");
                
        // Do we need to generate profile data?        
        // If so, load the Profile class and run it.
        if (config('log_benchmark') == true)
        {
            if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '')
            {
                $memory_usage = number_format($usage)." bytes";
            }
            else
            {
                $memory_usage = "memory_get_usage() function not found on your php configuration.";
            }

            $bench = \bench\getInstance(); // init to bencmark for profiling.
            
            $profile = array();
            foreach ($bench->marker as $key => $val)
            {
                // We match the "end" marker so that the list ends
                // up in the order that it was defined
                if (preg_match("/(.+?)_end/i", $key, $match))
                {             
                    if (isset($bench->marker[$match[1].'_end']) AND isset($bench->marker[$match[1].'_start']))
                    {
                        $profile[$match[1]] = \bench\elapsedTime($match[1].'_start', $key);
                    }
                }
            }
            
            foreach ($profile as $key => $val)
            {
                $key = ucwords(str_replace(array('_', '-'), ' ', $key));
                
                log\me('bench', "$key: ". $val); 
            }
             
            log\me('bench', "Memory Usage: ". $memory_usage); 
        } 
    }    
    
    // --------------------------------------------------------------------
    
    /**
    * Write a Cache File
    *
    * @access    public
    * @return    void
    */    
    public function _writeCache($output)
    {
        $OB = getInstance();
        $cache_path = APP .'cache'. DS;

        if ( ! is_dir(rtrim($cache_path, DS)) OR ! isReallyWritable(rtrim($cache_path, DS)))
        {
            return;
        }

        $uri_string = $OB->uri->uriString();  // Standart Uri
        $uri        = $OB->config->baseUrl() . $OB->config->item('index_page'). $uri_string;

        $cache_path .= md5($uri);

        if ( ! $fp = @fopen($cache_path, 'wb'))
        {
            log\me('error', 'Unable to write cache file: '.$cache_path);
            return;
        }
        
        $cache_expiration = $this->cache_expiration;
        $expire           = time() + ($cache_expiration * 60);
        
        if (flock($fp, LOCK_EX))
        {
            fwrite($fp, $expire.'TS--->'.$output);
            flock($fp, LOCK_UN);
        }
        else
        {
            log\me('error', 'Unable to secure a file lock for file at: '.$cache_path);
            return;
        }
        
        fclose($fp);
        @chmod($cache_path, 0777);

        log\me('debug', "Cache file written: ".$cache_path);
    }

    // --------------------------------------------------------------------
    
    /**
    * Update/serve a cached file
    *
    * @access    public
    * @return    void
    */    
    public function _displayCache(&$config, &$URI)
    {        
        $cache_path = APP .'cache'. DS;
        
        // Build the file path.  The file name is an MD5 hash of the full URI
        $uri =  $config->baseUrl() . $config->item('index_page') . $URI->uri_string;
        
        $filepath = $cache_path . md5($uri);

        if ( ! @file_exists($filepath))
        {
            return false;
        }

        if ( ! $fp = @fopen($filepath, 'rb'))
        {
            return false;
        }
            
        flock($fp, LOCK_SH);
        
        $cache_data = '';
        if (filesize($filepath) > 0)
        {
            $cache_data = fread($fp, filesize($filepath));
        }
    
        flock($fp, LOCK_UN);
        fclose($fp);
                    
        // Strip out the embedded timestamp        
        if ( ! preg_match("/(\d+TS--->)/", $cache_data, $match))
        {
            return false;
        }
        
        // Has the file expired? If so we'll delete it.
        if (time() >= trim(str_replace('TS--->', '', $match['1'])))
        {        
            if (isReallyWritable($cache_path))
            {
                @unlink($filepath);

                log\me('debug', 'Cache file has expired. File deleted');

                return false;
            }
        }

        // Display the cache
        $this->_display(str_replace($match['0'], '', $cache_data));
        
        log\me('debug', 'Cache file is current. Sending it to browser.');
        
        return true;
    }


}
// END Output Class

/* End of file Output.php */
/* Location: ./ob/output/releases/0.0.1/output.php */