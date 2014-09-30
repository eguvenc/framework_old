<?php

/**
 * Response Class
 * 
 * Set Http Response, Set Output Errors
 * Get Final Output
 *
 * @package       packages
 * @subpackage    response
 * @category      output
 * @link
 */
Class Response
{
    public $final_output;
    public $headers = array();

    // --------------------------------------------------------------------

    public function __construct()
    {
        global $logger;
        $logger->debug('Response Class Initialized');
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
        if ($this->final_output == '') {
            $this->final_output = $output;
        } else {
            $this->final_output.= $output;
        }
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
    public function _sendOutput($output = '')
    {
        global $config, $logger;

        if ($output == '') {  // Set the output data
            $output = & $this->final_output;
        }

        // Is compression requested?  
        // --------------------------------------------------------------------

        if ($config['compress_output']) {
            if (extension_loaded('zlib')) {
                if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
                    ob_start('ob_gzhandler');
                }
            }
        }

        // Are there any server headers to send ?
        // --------------------------------------------------------------------

        if (count($this->headers) > 0) {
            if ( ! headers_sent()) {
                foreach ($this->headers as $header) {
                    header($header[0], $header[1]);
                }
            }
        }

        // --------------------------------------------------------------------

        if ( ! function_exists('getInstance')) {     // Does the getInstance() function exist ? // If not we know we are dealing with a cache file so we'll
            echo $output;                            // simply echo out the data and exit. 
            return true;
        }

        if (method_exists(getInstance(), '_response')) {  // Does the controller contain a function named _output()?
            getInstance()->_response($output);          // If so send the output there.  Otherwise, echo it.
        } else {
            echo $output;  // Send it to the browser!
        }
    }

    // ------------------------------------------------------------------------
    
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
    * Set HTTP Status Header
    * 
    * @access   public
    * @param    int     the status code
    * @param    string    
    * @return   void
    */    
    public function setHttpResponse($code = 200, $text = '')
    {
        http_response_code($code);  // Php >= 5.4.0 
    }
        
    // ------------------------------------------------------------------------
    
    /**
     * Call helper functions
     * 
     * @param  string $method 
     * @param  array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        global $packages;

        if ( ! function_exists('Response\Src\\' . $method)) {
            include PACKAGES . 'response' . DS . 'releases' . DS . $packages['dependencies']['response']['version'] . DS . 'src' . DS . strtolower($method) . EXT;
        }
        return call_user_func_array('Response\Src\\' . $method, $arguments);
    }

}

// END Response Class

/* End of file Response.php */
/* Location: ./packages/response/releases/0.0.1/response.php */