<?php
namespace request {

    /**
    * Hmvc Request Helper
    * Do Request to another controller using HMVC Class.
    *
    * @package     Obullo
    * @subpackage  Helpers
    * @category    Hmvc
    * @link
    */

    Class start
    {
        function __construct()
        {
            \log\me('debug', 'Request Helper Initialized');
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * GET Method
     * 
     * @param string $uri
     * @param array $params
     * @param int | array $cache_time_or_config
     * @return string
     */
    function get($uri, $params = array(), $cache_time_or_config = '0')
    {
        return exec('get', $uri, $params, $cache_time_or_config);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * POST method
     * 
     * @param string $uri
     * @param array $params
     * @param int | array $cache_time_or_config
     * @return string
     */
    function post($uri, $params = array(), $cache_time_or_config = '0')
    {
        return exec('post', $uri, $params, $cache_time_or_config);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * PUT method
     * 
     * @param string $uri
     * @param array $params
     * @param int | array $cache_time_or_config
     * @return string
     */
    function put($uri, $params = array(), $cache_time_or_config = '0')
    {
        return exec('put', $uri, $params, $cache_time_or_config);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * DELETE method
     * 
     * @param string $uri
     * @param array $params
     * @param int | array $cache_time_or_config
     * @return string
     */
    function delete($uri, $params = array(), $cache_time_or_config = '0')
    {
        return exec('delete', $uri, $params, $cache_time_or_config);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Execute the request
     * 
     * @param string $method
     * @param string $request_uri
     * @param array $params
     * @param int | array $cache_time_or_config
     * @return string
     */
    function exec($method = 'get', $request_uri = '', $params = array(), $cache_time_or_config = '0')
    {
        $methods = array('GET' => '', 'POST' => '', 'PUT' => '', 'DELETE' => ''); // Supported request methods

        if( ! isset($methods[strtoupper($method)]))
        {
            if(is_numeric($params))
            {
                $cache_time_or_config = $params;
            }
            
            if(is_array($request_uri))
            {
                $params  = $request_uri;
            }

            // Long Access request
            if($request_uri === false)
            {   
                $hmvc = new Hmvc();  // Every hmvc request must create new instance.
                $hmvc->clear();               // Clear variables for each request.

                $hmvc->request($method);
                return $hmvc->exec();
                // $hmvc->setMethod($method, $params);
                // $hmvc->exec();
            }
            
            $request_uri = $method;
            $method      = 'GET';   // Set default method
        }

        $hmvc = new Hmvc();
        $hmvc->clear();                       
        $hmvc->request($request_uri, $cache_time_or_config);
        $hmvc->setMethod($method, $params);
    
        return $hmvc->exec();   // return to hmvc object
    }
    
}

/* End of file request.php */
/* Location: ./ob/request/releases/0.0.1/request.php */
