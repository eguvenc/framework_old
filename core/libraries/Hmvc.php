<?php

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

function ob_request_timer($mark = '')
{
    list($sm, $ss) = explode(' ', microtime());

    return ($sm + $ss);
}

 /**
 * HMVC Class
 * Hierarcial Model View Controller Library
 *
 * @package     Obullo
 * @subpackage  Libraries
 * @category    HMVC Request Class
 * @author      Ersin Guvenc
 * @version     0.1
 * @version     0.2  fixed this() bug, copied all this() object and assigned
 *              to instance using Controller::set_instance(); method.
 *              Hmvc router and uri libraries merged.
 */
Class OB_Hmvc
{
    // Cloned objects
    public $uri;                   // Clone original URI object
    public $router;                // Clone original Router object
    public $config;                // Clone original Config object
    public $_this         = NULL;  // Clone original this(); ( Controller instance)

    // Request, Response, Reset
    public $uri_string       = '';
    public $query_string     = '';
    public $response         = '';
    public $request_keys     = array();
    public $request_method   = 'GET';
    public $no_loop          = FALSE;
    public $cache_time       = '';
    public $is_reset         = FALSE;

    // Global variables
    public $_GET_BACKUP      = '';
    public $_POST_BACKUP     = '';
    public $_REQUEST_BACKUP  = '';
    public $_SERVER_BACKUP   = '';

    // Cache and Connection
    public $hmvc_connect         = TRUE;
    protected $_conn_string      = '';       // Unique HMVC connection string that we need to convert it to conn_id.
    protected static $_conn_id   = array();  // Static HMVC Connection ids.

    // Profiler and Benchmark
    // public static $request_times = array();   // request time for profiler
    public static $start_time    = '';        // benchmark start time for profiler
    public static $request_count = 0;         // request count for profiler

    // Rendering Output
    public $decode_format    = '';    // Json, Xml ..
    public $decode_assoc     = FALSE; // if true decode as (array) else decode as (object)
    
    public function __construct()
    {
        log_me('debug', "Hmvc Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
    * Prepare HMVC Request (Set the URI String).
    *
    * @access    private
    * @param     string $hvmc_uri
    * @param     int $cache_time
    * @return    void
    */
    public function request($hmvc_uri = '', $cache_time = 0)
    {
        $this->_set_conn_string($hmvc_uri);

        // Don't clone this(), we just do backup.
        #######################################
        
        $this->_this  = this();      // We need create backup $this object of main controller
                                     // becuse of it will change when using HMVC requests.
        
        #######################################
        
        if($hmvc_uri != '')
        {
            $URI     = lib('ob/Uri');
            $Router  = lib('ob/Router');
            $Config  = lib('ob/Config');
            
            # CLONE
            #######################################
            
            $this->uri     = clone $URI;     // Create copy of original URI class.
            $this->router  = clone $Router;  // Create copy of original Router class.
            $this->config  = clone $Config;  // Create copy of original Config class.
            
            # CLEAR
            #######################################

            $URI->clear();           // Reset uri objects we will reuse it for hmvc
            $Router->clear();        // Reset router objects we will reuse it for hmvc.

            #######################################
            
            $Router->hmvc = TRUE;    // We need to know Router class whether to use HMVC.

            #######################################
            
            $this->cache_time = $cache_time;
            $this->uri_string = $hmvc_uri;

            if(strpos($this->uri_string, '?') > 0)
            {
                $uri_part = explode('?', urldecode($this->uri_string));  // support any possible url encode operation
                $this->query_string = $uri_part[1];

                $URI->set_uri_string($uri_part[0], FALSE); // FALSE null filter
            }
            else
            {
                $URI->set_uri_string($this->uri_string);
            }
                    
            $this->hmvc_connect = $Router->_set_routing();      

            return $this;
        }

        $this->uri_string = '';

        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * Reset all variables for multiple
    * HMVC requests.
    *
    * @return   void
    */
    public function clear()
    {
        $this->_conn_string = '';
        $this->uri_string   = '';
        $this->query_string = '';
        $this->cache_time   = '';

        $this->reponse      = '';
        $this->request_keys = array();
        $this->hmvc_connect = TRUE;
        $this->no_loop      = FALSE;

        // Clone objects
        $this->uri          = '';
        $this->router       = '';
        $this->config       = '';
        $this->_this        = '';

        $this->request_method   = 'GET';

        // Global variables
        $this->_GET_BACKUP      = '';
        $this->_POST_BACKUP     = '';
        $this->_REQUEST_BACKUP  = '';
        $this->_SERVER_BACKUP   = '';
    }

    // --------------------------------------------------------------------

    /**
    * Set hmvc output cache time.
    * 
    * @param type $time 
    */
    public function cache($time = 0)
    {
        $this->cache_time = $time;
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Warning !!!
    * When we use HMVC in Parent Controllers (e.g. Welcome_Controller)
    * HMVC request will be in a unlimited loop, no_loop() function
    * will prevent this loop and any possible http server crashes (ersin).
    *
    * @param  bool $default
    * @return void
    */
    public function no_loop($default = TRUE)
    {
        $this->no_loop = $default;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * Set HMVC Request Method
    *
    * @param    string $method
    * @param    mixed  $params_or_data
    * @return   void
    */
    public function set_method($method = 'GET' , $params_or_data = array())
    {
        $method = $this->request_method = strtoupper($method);

        $this->_set_conn_string($method);        // Set Unique connection string foreach HMVC requests
        $this->_set_conn_string(serialize($params_or_data));

        if($this->query_string != '')
        {
            $query_str_params = $this->parse_query($this->query_string);

            if(count($query_str_params) > 0 AND ($method == 'GET' || $method == 'DELETE'))
            {
                if(is_array($params_or_data) AND count($params_or_data) > 0)
                {
                    $params_or_data = array_merge($query_str_params, $params_or_data);
                }
            }
        }

        // Original request variables
        $GLOBALS['_GET_BACKUP']    = $_GET;
        $GLOBALS['_POST_BACKUP']   = $_POST;
        $GLOBALS['_SERVER_BACKUP'] = $_SERVER;
        $GLOBALS['_SERVER_BACKUP'] = $_REQUEST;

        $this->_GET_BACKUP     = $_GET;         // Overload to $_REQUEST variables ..
        $this->_POST_BACKUP    = $_POST;
        $this->_SERVER_BACKUP  = $_SERVER;
        $this->_REQUEST_BACKUP = $_REQUEST;

        $GLOBALS['PUT'] = $_POST = $_GET = $_REQUEST = array();   // reset global variables
        
        unset($_SERVER['HTTP_ACCEPT']);    // Don't touch global server items 
        unset($_SERVER['REQUEST_METHOD']);
    
        switch ($method)
        {
           case 'POST':
            
            if( ! is_array($params_or_data))
            {
                throw new Exception('Data must be array when using HMVC POST methods.');
            }
               
            foreach($params_or_data as $key => $val)
            {
                $_POST[$key]    = urldecode($val);
                $_REQUEST[$key] = urldecode($val);

                $this->request_keys[$key] = '';
            }
             break;

           case ($method == 'GET' || $method == 'DELETE'):
            
            if( ! is_array($params_or_data))
            {
                throw new Exception('Data must be array when using HMVC GET or DELETE methods.');
            }
               
            foreach($params_or_data as $key => $val)
            {
                $_GET[$key]     = urldecode($val);
                $_REQUEST[$key] = urldecode($val);

                $this->request_keys[$key] = '';
            }
             break;

           case 'PUT':
            if(is_array($params_or_data) AND count($params_or_data) > 0)
            {
                foreach($params_or_data as $key => $val)
                {
                    $_REQUEST[$key] = urldecode($val);

                    $this->request_keys[$key] = '';
                }
            }
            else
            {
                $GLOBALS['PUT'] = $_REQUEST['PUT'] = $params_or_data;
            }
             break;
        }

        $_SERVER['REQUEST_METHOD']   = $method;  // Set request method ..
        $_SERVER['HMVC_REQUEST']     = TRUE;
        $_SERVER['HMVC_REQUEST_URI'] = $this->uri_string;
        
        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * Parse Url if there is any possible query string like this
    * hmvc_request('welcome/test/index?foo=im_foo&bar=im_bar');
    *
    * @param  string $query_string
    * @return array  $segments
    */
    public function parse_query($query_string = '')
    {
        if($query_string == '')
        {
            return array();
        }

        parse_str(html_entity_decode($query_string), $segments);

        return $segments;
    }

    // --------------------------------------------------------------------

    /**
    * Execute Hmvc Request
    *
    * @return   string
    */
    public function exec()
    {
        if($this->no_loop)
        {
            $conn_id = $this->_get_id();

            if( isset(self::$_conn_id[$conn_id]) )   // We need that to prevent HMVC loops if someone use hmvc request
            {                                        // in Application or Module Controller.
                $this->_reset_router(TRUE);

                return $this->_response();
            }

            self::$_conn_id[$conn_id] = $conn_id;    // store connection id.
        }

        $URI    = lib('ob/Uri');
        $router = lib('ob/Router');

        //------------------------------------
        self::$start_time = ob_request_timer('start');

        if($this->hmvc_connect === FALSE)
        {
            $this->set_response($router->hmvc_response);
            $this->_reset_router();

            return $this->_response();
        }
        
        // A Hmvc uri must be unique otherwise may collission with standart uri, 
        // also we need it for cache functionality.
        $URI->uri_string = rtrim($URI->uri_string, '/').'/_id_'. $this->_get_id();
        $URI->cache_time = $this->cache_time ;
    
        /*
        ob_start();
        // DISABLED HMVC CACHE IT SHOULD BE INTERNAL
        if($output->_display_cache($config, $URI, $router, TRUE) !== FALSE) // Check request uri if there is a HMVC cached file exist.
        {
            $cache_content = ob_get_contents();  if(ob_get_level() > 0) { ob_end_clean(); }
            
            $this->set_response($cache_content);

            $this->_reset_router();

            return $this->_response();
        }
        
        if(ob_get_level() > 0) ob_end_clean();
        */
        
        $hmvc_uri   = "{$router->fetch_directory()} / {$router->fetch_class()} / {$router->fetch_method()}";
        $controller = MODULES .$router->fetch_directory(). DS .'controllers'. DS .$router->fetch_class(). EXT;

        // Check the controller exists or not
        if ( ! file_exists($controller))
        {
            $this->set_response('404 - Hmvc request not found: Hmvc unable to load your controller.');

            $this->_reset_router();

            return $this->_response();
        }
     
        // Call the controller.
        require_once($controller);

        if ( ! class_exists($router->fetch_class()) OR $router->fetch_method() == 'controller'
              OR $router->fetch_method() == '_output'
              OR $router->fetch_method() == '_instance'
              OR in_array(strtolower($router->fetch_method()), array_map('strtolower', get_class_methods('Controller')))
            )
        {
            $this->set_response('404 - Hmvc request not found: '.$hmvc_uri);

            $this->_reset_router();

            return $this->_response();
        }

        $class = $router->fetch_class();
        
        // If Everyting ok Declare Called Controller !
        $OB = new $class();

        // Check method exist or not
        if ( ! in_array(strtolower($router->fetch_method()), array_map('strtolower', get_class_methods($OB))))
        {
            $this->set_response('404 - Hmvc request not found: '.$hmvc_uri);

            $this->_reset_router();

            return $this->_response();
        }
        
        ob_start();

        //
        // Call the requested method.                1       2       3
        // Any URI segments present (besides the directory/class/method)
        // will be passed to the method for convenience
        call_user_func_array(array($OB, $router->fetch_method()), array_slice($URI->rsegments, 3));

        $content = ob_get_contents();       

        if(ob_get_level() > 0)  ob_end_clean();   
                       
        // Write cache file if cache on ! and Send the final rendered output to the browser
        // 
        ################ DISPLAY CACHE FILE  WE WILL DO IT LATER ##################
        # ob_start();
        #
        # 
        # $output->_display_hmvc($content, $URI);
        #
        #        
        # $content = ob_get_contents();
        # if(ob_get_level() > 0)  ob_end_clean(); 
        #
        ################ DISPLAY CACHE FILE END ##################        
                                        
        $this->set_response($content); 

        $this->_reset_router();    
                       
        log_me('debug', 'Hmvc process completed succesfully.');
        log_me('info', 'Hmvc Output: '.$this->_response());
        
        return $this->_response();
    }

    // --------------------------------------------------------------------

    /**
    * Reset router for mutiple hmvc requests
    * or who want to close the hmvc connection.
    *
    * @param    boolean $no_loop anti loop
    * @return   void
    */
    protected function _reset_router($no_loop = FALSE)
    {
        $GLOBALS['PUT'] = $_SERVER = $_POST = $_GET = $_REQUEST = array();
        
        # Assign global variables we copied before ..
        ######################################
        
        $_GET     = $this->_GET_BACKUP;           
        $_POST    = $this->_POST_BACKUP;
        $_SERVER  = $this->_SERVER_BACKUP;
        $_REQUEST = $this->_REQUEST_BACKUP;

        # Set original objects foreach HMVC requests we backup before  ..
        ######################################
        
        $URI = lib('ob/Uri');

        $this->_this->uri     = lib('ob/URI', '', $this->uri);
        $this->_this->router  = lib('ob/Router', '', $this->router);
        $this->_this->config  = lib('ob/Config', '', $this->config);

        this($this->_this);         // Set original $this to controller instance that we backup before
    
        # Assign Obullo global variables ..
        ######################################

        $this->clear();  // reset all HMVC variables.
        
        ######################################

        if($no_loop == FALSE)
        {
            ++self::$request_count;

            $end_time = ob_request_timer('end'); // Profiler

            log_me('info', 'Hmvc Request: '.$URI->uri_string.' time: '.number_format($end_time - self::$start_time, 4));
            
            // self::$request_times[$URI->uri_string] = $end_time - self::$start_time;
        }
        
        $this->is_reset = TRUE;  // This means hmvc process completed succesfully without any errors.
                                 // If is_reset == FALSE we say to destruct method reset the router
                                 // variables and turn back to orginial vars of obullo which we had reset them before.        
    }

    // --------------------------------------------------------------------

    /**
    * Set $_SERVER vars foreach hmvc
    * requests.
    *
    * @param string $key
    * @param mixed  $val
    */
    public function set_server($key, $val)
    {
        $_SERVER[$key] = $val;

        $this->_set_conn_string($key.$val);
        
        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * Set hmvc response.
    *
    * @param    mixed $data
    * @return   void
    */
    public function set_response($data = '')
    {
        $this->response = $data;
    }

    // --------------------------------------------------------------------

    /**
    * Get none-decoded original Hmvc
    * response.
    * 
    * @return string
    */
    public function response()
    {
        return $this->response;
    }
   
    // --------------------------------------------------------------------
    
    /**
    * Render final hmvc output.
    *
    * @access private
    * @return   string
    */
    public function _response()
    {   
        if($this->decode_format == 'json')
        {
           $row = json_decode($this->response(), $this->decode_assoc);
            
           $this->_reset_decode_vars();
            
           return $row;
        }
        
        return $this->response();
    }

    // --------------------------------------------------------------------
    
    /**
    * Create HMVC connection string next
    * we will convert it to connection id.
    *
    * @param    mixed $id
    */
    protected function _set_conn_string($id)
    {
        $this->_conn_string .= $id;
    }

    // --------------------------------------------------------------------

    /**
    * Convert connection string to HMVC
    * connection id.
    *
    * @return   string
    */
    protected function _get_id()
    {
        return md5(trim($this->_conn_string));
    }

    // --------------------------------------------------------------------
    
    /**
    * Decode encoded string.
    * Default json.
    * 
    * @return object
    */
    public function decode($format = 'json', $assoc = FALSE)
    {
        $this->decode_format = strtolower($format);
        $this->decode_assoc  = (bool)$assoc; // else decode as object
        
        return $this;
    }
    
    // --------------------------------------------------------------------

    /**
    * Reset the decoding variables 
    * before return to hmvc response.  
    * 
    * @access private
    * @return void 
    */
    public function _reset_decode_vars()
    {
        $this->decode_format = '';
        $this->decode_assoc  = FALSE;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Close HMVC Connection
    * 
    * If we have any possible hmvc exceptions
    * reset the router variables, complete to HMVC process
    * and return to original vars.
    * 
    * @return void
    */
    public function __destruct()
    {                 
        if($this->is_reset == FALSE)         
        {                                   
            $this->_reset_router($this->no_loop);
            
            return;
        }

        $this->is_reset = FALSE;
    }

}
// END Hmvc Class

/* End of file Hmvc.php */
/* Location: ./obullo/libraries/Hmvc.php */