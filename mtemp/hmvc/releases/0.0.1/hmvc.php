<?php

function requestTimer($mark = '')
{
    list($sm, $ss) = explode(' ', microtime());

    return ($sm + $ss);
}

 /**
 * HMVC Class
 * Hierarcial Model View Controller Library
 *
 * @package       packages
 * @subpackage    hmvc
 * @category      hmvc
 * 
 */
Class Hmvc
{
    // Controller Object
    public $_this            = null;  // Clone original getInstance(); ( Controller instance )

    // Request, Response, Reset
    public $uri_string       = '';
    public $query_string     = '';
    public $response         = '';
    public $request_keys     = array();
    public $request_method   = 'GET';
    public $no_loop          = false;
    public $cache_time       = '';
    public $is_reset         = false;

    // Clone objects
    public $uri          = '';
    public $router       = '';
    public $config       = '';
        
    // Global variables
    public $_GET_BACKUP      = '';
    public $_POST_BACKUP     = '';
    public $_REQUEST_BACKUP  = '';
    public $_SERVER_BACKUP   = '';

    // Cache and Connection
    public $hmvc_connect         = true;
    protected $_conn_string      = '';       // Unique HMVC connection string that we need to convert it to conn_id.
    protected static $_conn_id   = array();  // Static HMVC Connection ids.

    // Profiler and Benchmark
    public static $start_time    = '';        // benchmark start time for profiler
    public static $request_count = 0;         // request count for profiler
    
    public function __construct()
    {
        log\me('debug', "Hmvc Class Initialized");
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
        $this->_setConnString($hmvc_uri);

        // Don't clone getInstance(), we just do backup.
        #######################################
        
        $this->_this = getInstance();      // We need create backup $this object of main controller
                                                // becuse of it will change when HMVC process is done.
        
        #######################################
        
        if($hmvc_uri != '')
        {
            $URI     = getInstance()->uri;
            $Router  = getInstance()->router;
            $Config  = getInstance()->config;
            
            # CLONE
            #######################################
            
            $this->uri     = clone $URI;     // Create copy of original URI class.
            $this->router  = clone $Router;  // Create copy of original Router class.
            $this->config  = clone $Config;  // Create copy of original Router class.

            
            # CLEAR
            #######################################

            getInstance()->uri->clear();           // Reset uri objects we will reuse it for hmvc
            getInstance()->router->clear();        // Reset router objects we will reuse it for hmvc.

            #######################################
            
            $Router->hmvc = true;    // We need to know Router class whether to use HMVC.

            #######################################
            
            $this->cache_time = $cache_time;
            $this->uri_string = $hmvc_uri;

            if(strpos($this->uri_string, '?') > 0)
            {
                $uri_part = explode('?', urldecode($this->uri_string));  // support any possible url encode operation
                $this->query_string = $uri_part[1];

                $URI->setUriString($uri_part[0], false); // false null filter
            }
            else
            {
                $URI->setUriString($this->uri_string);
            }
                    
            $this->hmvc_connect = $Router->_setRouting();      

            return $this;
        }

        $this->uri_string = '';

        return ($this);
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
        $this->hmvc_connect = true;
        $this->no_loop      = false;

        // Clone objects
        $this->uri          = '';
        $this->router       = '';
        $this->config       = '';
       
        $this->_this            = '';
        $this->request_method   = 'GET';

        // Global variables
        $this->_GET_BACKUP      = '';
        $this->_POST_BACKUP     = '';
        $this->_REQUEST_BACKUP  = '';
        $this->_SERVER_BACKUP   = '';
    }

    // --------------------------------------------------------------------

    /**
    * @todo
    * Set hmvc output cache time.
    * 
    * @param type $time 
    */
    public function cache($time = 0)
    {
        $this->cache_time = $time;
        
        return ($this);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Warning !!!
    * When we use HMVC in Parent Controllers (e.g. Welcome_Controller)
    * HMVC request will be in a unlimited loop, noLoop() function
    * will prevent this loop and any possible http server crashes (ersin).
    *
    * @param  bool $default
    * @return void
    */
    public function noLoop($default = true)
    {
        $this->no_loop = $default;

        return ($this);
    }

    // --------------------------------------------------------------------

    /**
    * Set HMVC Request Method
    *
    * @param    string $method
    * @param    mixed  $params_or_data
    * @return   void
    */
    public function setMethod($method = 'GET' , $params_or_data = array())
    {
        $method = $this->request_method = strtoupper($method);

        $this->_setConnString($method);        // Set Unique connection string foreach HMVC requests
        $this->_setConnString(serialize($params_or_data));

        if($this->query_string != '')
        {
            $query_str_params = $this->parseQuery($this->query_string);

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
        $_SERVER['HMVC_REQUEST']     = true;
        $_SERVER['HMVC_REQUEST_URI'] = $this->uri_string;
        
        return ($this);
    }

    // --------------------------------------------------------------------

    /**
    * Parse Url if there is any possible query string like this
    * hmvc_request('welcome/test/index?foo=im_foo&bar=im_bar');
    *
    * @param  string $query_string
    * @return array  $segments
    */
    public function parseQuery($query_string = '')
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
            $conn_id = $this->_getId();

            if( isset(self::$_conn_id[$conn_id]) )   // We need that to prevent HMVC loops if someone use hmvc request
            {                                        // in Application or Module Controller.
                $this->_resetRouter(true);

                return $this->_response();
            }

            self::$_conn_id[$conn_id] = $conn_id;    // store connection id.
        }

        $URI    = getInstance()->uri;
        $router = getInstance()->router;

        //------------------------------------
        self::$start_time = requestTimer('start');

        if($this->hmvc_connect === false)
        {
            $this->setResponse($router->hmvc_response);
            $this->_resetRouter();

            return $this->_response();
        }
        
        // A Hmvc uri must be unique otherwise may collission with standart uri, 
        // also we need it for cache functionality.
        $URI->uri_string = rtrim($URI->uri_string, '/').'/_id_'. $this->_getId();
        $URI->cache_time = $this->cache_time ;
    
        // @todo do ob_start(); HMVC CACHE if(ob_get_level() > 0) ob_end_clean();
        
        $hmvc_uri   = "{$router->fetchDirectory()} / {$router->fetchClass()} / {$router->fetchMethod()}";
        $controller = MODS .$router->fetchDirectory(). DS .'controller'. DS .$router->fetchClass(). EXT;

        // Check the controller exists or not
        if ( ! file_exists($controller))
        {
            $this->setResponse('404 - Hmvc request not found: Unable to load your controller.');
            $this->_resetRouter();

            return $this->_response();
        }
     
        // Call the controller.
        require_once($controller);

        if ( ! class_exists($router->fetchClass()) OR $router->fetchMethod() == 'controller' 
              OR $router->fetchMethod() == '_output'       // security fix.
              OR $router->fetchMethod() == '_ob_getInstance_'
              OR in_array(strtolower($router->fetchMethod()), array_map('strtolower', get_class_methods('Controller')))
            )
        {
            $this->setResponse('404 - Hmvc request not found: '.$hmvc_uri);
            $this->_resetRouter();

            return $this->_response();
        }

        $Class = $router->fetchClass();
        
        // If Everyting ok Declare Called Controller !
        $ControllerClass = new $Class();

        // Check method exist or not
        if ( ! in_array(strtolower($router->fetchMethod()), array_map('strtolower', get_class_methods($ControllerClass))))
        {
            $this->setResponse('404 - Hmvc request not found: '.$hmvc_uri);
            $this->_resetRouter();

            return $this->_response();
        }
        
        ob_start();

        // Call the requested method.                1       2       3
        // Any URI segments present (besides the directory/class/method)
        // will be passed to the method for convenience
        call_user_func_array(array($ControllerClass, $router->fetchMethod()), array_slice($URI->rsegments, 3));

        $content = ob_get_contents();       

        if(ob_get_level() > 0)  ob_end_clean();      
                                        
        $this->setResponse($content); 

        $this->_resetRouter();    
                       
        log\me('debug', 'Hmvc process is done.');
        log\me('info', 'Hmvc output: '.$this->_response());
        
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
    protected function _resetRouter($no_loop = false)
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
        
        $URI = getInstance()->uri;
        
        $uri    = getComponent('uri');
        $router = getComponent('router');
        $config = getComponent('config');
        
        $this->_this->uri     = $uri::setInstance($this->uri);
        $this->_this->router  = $router::setInstance($this->router);
        $this->_this->config  = $config::setInstance($this->config);
        
        getInstance($this->_this);         // Set original $this to controller instance that we backup before.
    
        # Assign Obullo global variables ..
        ######################################

        $this->clear();  // reset all HMVC variables.
        
        ######################################

        if($no_loop == false)
        {
            ++self::$request_count;

            $end_time = requestTimer('end'); // Profiler

            log\me('info', 'Hmvc request: '.$URI->uri_string.' time: '.number_format($end_time - self::$start_time, 4));
            
            // self::$request_times[$URI->uri_string] = $end_time - self::$start_time;
        }
        
        $this->is_reset = true;  // This means hmvc process done without any errors.
                                 // If is_reset == false we say to destruct method "reset the router" variables 
                                 // and return to original variables of Obullo that we clone them before the hmvc request. 
    }

    // --------------------------------------------------------------------

    /**
    * Set $_SERVER vars foreach hmvc
    * requests.
    *
    * @param string $key
    * @param mixed  $val
    */
    public function setServer($key, $val)
    {
        $_SERVER[$key] = $val;

        $this->_setConnString($key.$val);
        
        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * Set hmvc response.
    *
    * @param    mixed $data
    * @return   void
    */
    public function setResponse($data = '')
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
        return $this->response();
    }

    // --------------------------------------------------------------------
    
    /**
    * Create HMVC connection string next
    * we will convert it to connection id.
    *
    * @param    mixed $id
    */
    protected function _setConnString($id)
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
    protected function _getId()
    {
        return md5(trim($this->_conn_string));
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
        if($this->is_reset == false)         
        {                                   
            $this->_resetRouter($this->no_loop);
            
            return;
        }

        $this->is_reset = false;
    }

}
// END Hmvc Class

/* End of file hmvc.php */
/* Location: ./packages/hmvc/releases/0.0.1/hmvc.php */