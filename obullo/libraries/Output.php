<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2009.
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Output Class
 *
 * Responsible for sending final output to browser  
 *
 * @package       Obullo
 * @subpackage    Libraries
 * @category      Libraries
 * @author        Ersin Guvenc
 * @link          
 * @version       0.1
 * @version       0.2  Added HMVC Output Cache Support
 * @version       0.3  @deprecated HMVC Output Cache, @deprecated submodules cache support ( for performance )
 */
Class OB_Output {

    public $final_output;
    public $cache_expiration    = 0;
    public $headers             = array();
    public $enable_profiler     = FALSE;
    public $parse_exec_vars     = TRUE;    // whether or not to parse variables like {elapsed_time} and {memory_usage}
    
    public function __construct()
    {
        log_me('debug', "Output Class Initialized");
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
    public function get_output()
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
    public function set_output($output)
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
    public function append_output($output)
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
    public function set_header($header, $replace = TRUE)
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
    public function set_status_header($code = 200, $text = '')
    {
        set_status_header($code, $text);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Enable/disable Profiler
    *
    * @access   public
    * @param    bool  $val
    * @return   void
    */    
    public function profiler($val = TRUE)
    {
        $this->enable_profiler = (is_bool($val)) ? $val : TRUE;
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
            $this->_write_cache($output);
        }
        
        // --------------------------------------------------------------------

        // Parse out the elapsed time and memory usage,
        // then swap the pseudo-variables with the data
        
        $elapsed = benchmark_elapsed_time('total_execution_time_start', 'total_execution_time_end');        
        $output  = str_replace('{elapsed_time}', $elapsed, $output);
                
        if ($this->parse_exec_vars === TRUE)
        {
            $memory = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB';
            $output = str_replace('{elapsed_time}', $elapsed, $output);
            $output = str_replace('{memory_usage}', $memory, $output);
        }       

        // Is compression requested?  
        // --------------------------------------------------------------------
        
        if (config('compress_output') === TRUE)
        {
            if (extension_loaded('zlib'))
            {             
                if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE)
                {   
                    // Obullo changes .. 
                    ini_set('zlib.output_compression_level', config('compression_level'));  
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
        
        // Does the this() function exist?
        // If not we know we are dealing with a cache file so we'll
        // simply echo out the data and exit.
        if ( ! function_exists('this'))
        {
            echo $output;
            
            log_me('debug', "Final output sent to browser");
            log_me('debug', "Total execution time: ".$elapsed);
            
            return TRUE;
        }
    
        // Profiler
        // --------------------------------------------------------------------
        
        // Do we need to generate profile data?        
        // If so, load the Profile class and run it.
        if ($this->enable_profiler == TRUE)
        {
            // Get profiler output.
            $profiler       = lib('ob/Profiler');
            $data['output'] = $profiler->run();
            
                                 
            // If the output data contains closing </body> and </html> tags
            // we will remove them and add them back after we insert the profiler script
            if (preg_match("|</body>.*?</html>|is", $output))
            {
                $output  = preg_replace("|</body>.*?</html>|is", '', $output);
                
                // Add profiler script before the body end tag. ( Obullo Changes )
                $output .= view('ob/profiler', $data);  
                $output .= '</body></html>';
            }
            else
            {
                $output .= view('ob/profiler', $data);
            }
        } 
        
        // Does the controller contain a function named _output()?
        // If so send the output there.  Otherwise, echo it.
        $ob = this();
        
        if (method_exists($ob, '_output'))
        {
            $ob->_output($output);
        }
        else
        {
            echo $output;  // Send it to the browser!
        }
        
        log_me('debug', "Final output sent to browser");
        log_me('debug', "Total execution time: " . $elapsed);        
    }    
    
    // --------------------------------------------------------------------
    
    /**
    * Write a Cache File
    *
    * @access    public
    * @return    void
    */    
    public function _write_cache($output)
    {
        $OB = this();
        $config = lib('ob/Config');
        
        $path       = $config->item('cache_path');
        $cache_path = ($path == '') ?  APP .'core'. DS .'cache'. DS : $path;

        if ( ! is_dir(rtrim($cache_path, DS)) OR ! is_really_writable(rtrim($cache_path, DS)))
        {
            return;
        }

        $uri_string = $OB->uri->uri_string();  // Standart Uri
        $uri        = $OB->config->base_url() . $OB->config->item('index_page'). $uri_string;

        $cache_path .= md5($uri);

        if ( ! $fp = @fopen($cache_path, FOPEN_WRITE_CREATE_DESTRUCTIVE))
        {
            log_me('error', 'Unable to write cache file: '.$cache_path);
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
            log_me('error', 'Unable to secure a file lock for file at: '.$cache_path);
            return;
        }
        
        fclose($fp);
        @chmod($cache_path, DIR_WRITE_MODE);

        log_me('debug', "Cache file written: ".$cache_path);
    }

    // --------------------------------------------------------------------
    
    /**
    * Update/serve a cached file
    *
    * @access    public
    * @return    void
    */    
    public function _display_cache(&$config, &$URI, &$router)
    {        
        $cache_path = ($config->item('cache_path') == '') ? APP .'core'. DS .'cache'. DS : $config->item('cache_path');
        
        // Build the file path.  The file name is an MD5 hash of the full URI
        $uri =  $config->base_url() . $config->item('index_page') . $URI->uri_string;
        
        $filepath = $cache_path . md5($uri);

        if ( ! @file_exists($filepath))
        {
            return FALSE;
        }

        if ( ! $fp = @fopen($filepath, FOPEN_READ))
        {
            return FALSE;
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
            return FALSE;
        }
        
        // Has the file expired? If so we'll delete it.
        if (time() >= trim(str_replace('TS--->', '', $match['1'])))
        {        
            if (is_really_writable($cache_path))
            {
                @unlink($filepath);
                
                log_me('debug', 'Cache file has expired. File deleted');
                
                return FALSE;
            }
        }

        // Display the cache
        $this->_display(str_replace($match['0'], '', $cache_data));
        
        log_me('debug', 'Cache file is current. Sending it to browser.');
        
        return TRUE;
    }


}
// END Output Class

/* End of file Output.php */
/* Location: ./obullo/libraries/Output.php */