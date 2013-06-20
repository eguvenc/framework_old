<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo
 * @author          obullo.com
 * @license         public
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Hmvc Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Obullo Team
 * @link
 */

// ------------------------------------------------------------------------

/**
* Call HMVC Request using HMVC Class.
*
* @access   public
* @param    string method
* @param    string  $request
* @param    integer | bool | array  $cache_time_or_config
* @return   string response | object of HMVC class
*/
if( ! function_exists('request') )
{
    function request($method = 'get', $request_uri = '', $params = array(), $cache_time_or_config = '0')
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
                $hmvc = lib('Hmvc', '', true);   // Every hmvc request must create new instance (true == new instance).
                $hmvc->clear();                  // Clear variables for each request.

                $hmvc->request($method);

                return $hmvc;
                // $hmvc->set_method($method, $params);
                // $hmvc->exec();
            }
            
            $request_uri = $method;
            $method      = 'GET';   // Set default method
        }

        $hmvc = lib('Hmvc', '', true); 
        $hmvc->clear();                       
        $hmvc->request($request_uri, $cache_time_or_config);
        $hmvc->set_method($method, $params);
    
        return $hmvc;   // return to hmvc object
    }

}

/* End of file request.php */
/* Location: ./obullo/helpers/request.php */
