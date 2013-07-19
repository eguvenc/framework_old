<?php
namespace Ob\request {

/**
* Call HMVC Request using HMVC Class.
*
* @access   public
* @param    string method
* @param    string  $request
* @param    integer | bool | array  $cache_time_or_config
* @return   string response
*/
    
    function get($uri, $params, $cache_time_or_config)
    {
        return exec('get', $uri, $params, $cache_time_or_config);
    }
    
    function post($uri, $params, $cache_time_or_config)
    {
        return exec('post', $uri, $params, $cache_time_or_config);
    }
    
    function put($uri, $params, $cache_time_or_config)
    {
        return exec('post', $uri, $params, $cache_time_or_config);
    }
    
    function delete($uri, $params, $cache_time_or_config)
    {
        return exec('post', $uri, $params, $cache_time_or_config);
    }
    
    function exec($method = 'get', $request_uri = '', $params = array(), $cache_time_or_config = '0')
    {
        // Supported request methods
        $methods = array('GET' => '', 'POST' => '', 'PUT' => '', 'DELETE' => '');

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
            if($request_uri === FALSE)
            {   
                $hmvc = new Hmvc();   // Every hmvc request must create new instance (true == new instance).
                $hmvc->clear();       // Clear variables for each request.

                $hmvc->request($method);

                return $hmvc->exec();
                // $hmvc->set_method($method, $params);
                // $hmvc->exec();
            }
            
            $request_uri = $method;
            $method      = 'GET';   // Set default method
        }

        $hmvc = new Hmvc();
        $hmvc->clear();                       
        $hmvc->request($request_uri, $cache_time_or_config);
        $hmvc->set_method($method, $params);
    
        return $hmvc->exec();   // return to hmvc object
    }
    
}

/* End of file request.php */
/* Location: ./ob/request/releases/0.0.1/request.php */
