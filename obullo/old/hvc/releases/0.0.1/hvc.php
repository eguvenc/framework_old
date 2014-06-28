<?php

/**
 * Hvc Class
 * "Hierarcial View Controller" Library
 * 2009 -2014
 * 
 * @author        Obullo - obulloframework@gmail.com
 * @package       packages
 * @subpackage    hvc
 * @category      hvc
 * 
 */
Class Hvc
{
    // Controller Object
    public $global = null;     // Global instance of the controller object we need to clone it.
    public $config = array();  // hvc configuration

    // Request, Response, Reset
    public $query_string = '';
    public $response = '';
    public $request_method = 'GET';
    public $process_done = false;

    // Clone objects
    public $uri    = null;
    public $router = null;

    // Cache and Connection
    public $connection = true;
    protected $conn_string = '';       // Unique HVC connection string that we need to convert it to conn_id.
    protected static $cid  = array();  // Static HVC Connection ids. DO NOT CLEAR IT !!!
    protected $hvc_uri;

    const KEY = 'Hvc:';   // Hvc key prefix

    // Benchmark
    public static $start_time = '';     // benchmark start time

    // --------------------------------------------------------------------

    /**
     * Reset all variables for multiple
     * HVC requests.
     *
     * @return   void
     */
    public function clear()
    {
        // Controller Object
        $this->global = null;     // Global instance of the controller object

        // Request, Response, Reset
        $this->reponse        = '';
        $this->request_method = 'GET';
        $this->process_done   = false;

        // Clone objects
        $this->uri    = null;
        $this->router = null;

        // Cache and Connection
        $this->connection  = true;

        // $GLOBALS['_GET_BACKUP']     = array();    // Reset global variables
        // $GLOBALS['_POST_BACKUP']    = array();
        $GLOBALS['_SERVER_BACKUP']  = array();
        // $GLOBALS['_REQUEST_BACKUP'] = array();

        // unset($_SERVER['HVC_REQUEST']);          // Don't touch global hvc headers
        // unset($_SERVER['HVC_REQUEST_URI']);      // otherwise we can't use (hvc in hvc) loop.
        // unset($_SERVER['HVC_REQUEST_TYPE']);
    }

    // --------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct()
    {
        global $logger;

        if ( ! isset(getInstance()->hvc)) {          // Like Singleton
            getInstance()->translator->load('hvc'); // Load translate file
            getInstance()->hvc = $this;             // Make available it in the controller $this->hvc->method();
        }
        $this->config = getConfig('hvc');       // Get hvc configuration

        $logger->debug('Hvc Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Prepare HVC Request (Set the URI String).
     *
     * @access private
     * @param string $uri uri
     * 
     * @return    void
     */
    public function setRequestUrl($uriString = '')
    {
        // ----------- Visibility -----------------

        $type = 'public';
        $uriString = trim($uriString, '/');

        if (strpos($uriString, 'private/') === 0) { // Set the visibility of hvc request
            $type = 'private';
            $uriString = substr($uriString, 8);
        }

        if (strpos($uriString, 'public/') === 0) { // Set the visibility of hvc request
            $type = 'public';
            $uriString = substr($uriString, 6);
        }

        //-------- BACKUP GLOBALS ( We need them in Get Class ) ---------//

        $GLOBALS['_GET_BACKUP']     = $_GET;    // Original request variables
        $GLOBALS['_POST_BACKUP']    = $_POST;
        $GLOBALS['_SERVER_BACKUP']  = $_SERVER;
        $GLOBALS['_REQUEST_BACKUP'] = $_REQUEST;

        //--------- 

        unset($_SERVER['HTTP_ACCEPT']);    // Don't touch global server items 
        unset($_SERVER['REQUEST_METHOD']);

        $_SERVER['HVC_REQUEST']      = true;   // Set Hvc Headers
        $_SERVER['HVC_REQUEST_TYPE'] = $type;  // "public" or "private"
        //--------------------------

        $this->_setConnString($uriString);

        // Don't clone getInstance(), we just do backup.
        //----------------------------------------------
        
        $this->global = getInstance();     // We need create backup $this object of main controller

        // becuse of it will change when HVC process is done.
        //----------------------------------------------

        if ( ! empty($uriString)) { // empty control

            global $uri, $router;

            // Clone Objects
            // -----------------------------------------

            $this->uri    = clone $uri;         // Create copy of original Uri class.
            $this->router = clone $router;      // Create copy of original Router class.

            // Clear
            // -----------------------------------------

            $uri->clear();           // Reset uri objects we will reuse it for hvc
            $router->clear();        // Reset router objects we will reuse it for hvc.
            // -----------------------------------------
            //----------------------------------------------
            // Set Uri String to Uri Object
            //----------------------------------------------

            if (strpos($uriString, '?') > 0) {
                $uri_part = explode('?', urldecode($uriString));  // support any possible url encode operation
                $this->query_string = $uri_part[1];     // .json?id=2

                $uri->setUriString($uri_part[0], false); // false = null filter
            } else {
                $uri->setUriString($uriString);
            }

            // Set uri string to $_SERVER GLOBAL
            //----------------------------------------------

            $_SERVER['HVC_REQUEST_URI'] = $uriString;

            //----------------------------------------------

            $this->connection = $router->_setRouting(); // Returns false if we have hvc connection error.

            //----------------------------------------------
        }
    }

    // --------------------------------------------------------------------

    /**
     * Set Hvc Request Method
     *
     * @param    string $method
     * @param    mixed  $data   params or data
     * @return   void
     */
    public function setMethod($method = 'GET', $data = '')
    {
        if (empty($data)) {
            $data = array();
        }
        $method = $this->request_method = strtoupper($method);

        $this->_setConnString($method);        // Set Unique connection string foreach HVC requests
        $this->_setConnString(serialize($data));

        if ($this->query_string != '') {
            $querStringParams = $this->parseQuery($this->query_string);
            if (sizeof($data) > 0) {
                $data = array_merge($querStringParams, $data);
            }
        }
        foreach ($data as $key => $val) {
            switch ($method) {
            case ($method == 'GET' OR $method == 'DELETE'):
                $_GET[$key] = is_string($val) ? urldecode($val) : $val;
                break;
            default:
                $_POST[$key] = $val;
                break;
            }
            $_REQUEST[$key] = $val;
        }
        $_SERVER['REQUEST_METHOD'] = $method;  // Set request method ..
    }

    // --------------------------------------------------------------------
    
    /**
     * Parse Url if there is any possible query string like this
     *
     * $this->hvc->get('welcome/test/index?foo=im_foo&bar=im_bar');
     *
     * @param  string $query_string
     * @return array  $segments
     */
    public function parseQuery($query_string = '')
    {
        if ($query_string == '') {
            return array();
        }
        parse_str(html_entity_decode($query_string), $segments);
        return $segments;
    }

    // ------------------------------------------------------------------------

    /**
     * Hvc Get Request
     * 
     * @param  string $uri    
     * @param  array  $data request data ( $_POST or $_GET )
     * @param  integer $expiration whether to use cache
     * @return string         
     */
    public function get($uri, $data = '', $expiration = null)
    {
        return $this->request('GET', $uri, $data, $expiration);
    }

    // ------------------------------------------------------------------------

    /**
     * Hvc Post Request
     * 
     * @param  string $uri    
     * @param  mixed  $data request data ( $_POST or $_GET )
     * @param  integer $expiration whether to use cache
     * @return string         
     */
    public function post($uri, $data = '', $expiration = null)
    {
        return $this->request('POST', $uri, $data, $expiration);
    }

    // ------------------------------------------------------------------------

    /**
     * Hvc Put Request
     * 
     * @param  string $uri    
     * @param  array  $data request data ( $_POST or $_GET )
     * @return string         
     */
    public function put($uri, $data = '')
    {
        return $this->request('PUT', $uri, $data);
    }

    // ------------------------------------------------------------------------

    /**
     * Hvc Delete Request
     * 
     * @param  string $uri    
     * @param  array  $data request data ( $_POST or $_GET )
     * @return string
     */
    public function delete($uri, $data = '')
    {
        return $this->request('DELETE', $uri, $data);
    }

    // ------------------------------------------------------------------------

    /**
     * Get visibility of request Private / Public
     * 
     * @return string
     */
    public function getVisibility()
    {
        return (isset($_SERVER['HVC_REQUEST_TYPE'])) ? $_SERVER['HVC_REQUEST_TYPE'] : 'public';
    }

    // ------------------------------------------------------------------------

    /**
     * Do request 
     * 
     * @param  string  $uri
     * @param  array   $data request data ( $_POST or $_GET )
     * @param  integer $ttl
     * @return string
     */
    public function request($method, $uri, $data = '', $expiration = null)
    {
        if ($expiration === true) {  // delete cache before the request
            $this->deleteCache();
        }
        if (is_numeric($data)) { // set expiration as second param if data not provided
            $expiration = $data;
            $data = array();
        }
        $this->clear();
        $this->setRequestUrl($uri, $expiration);
        $this->setMethod($method, $data);

        $vsb = $this->getVisibility();
        $rsp = $this->exec($expiration); // execute the process

        $errorHeader = '<div style="white-space: pre-wrap;white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap;word-wrap: break-word;
  background:#fff;border:1px solid #ddd;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;padding:5px 10px;color:#069586;font-size:12px;"><span style="font-weight:bold;">';
        $errorFooter = '</div>';
        $hvc_error = $errorHeader .'</span><span style="font-weight:bold;">Private hvc response must be array and contain at least one of the following keys. ( "private/views" route is excluded ).</span><pre style="border:none;">
$r = array(
    \'success\' => integer     // optional
    \'message\' => string,     // optional
    \'errors\'  => array(),    // optional
    \'results\' => array(),    // optional
    \'e\' => $e->getMessage(), // optional
)

echo json_encode($r); // required

<b>Actual response:</b> '.$rsp.'
</pre>' . $errorFooter;
        $hvc_view_error = $errorHeader . '</span><span style="font-weight:bold;">View Controller ( ' . $this->getUri() . ' ) method must echo a string, should not be empty.</span><pre style="border:none;">
echo $this->view->get(
    \'header\',
    function () {
    },
    false  // required
);</pre>' . $errorFooter;

        $isXmlHttp = ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : false;

        if (is_string($rsp) AND strpos($rsp, '404') === 0) {  // 404 support
            if ($isXmlHttp) {  // ajax support
                return array(
                    'success' => 0,
                    'message' => translate('e_404'),
                    'errors' => array()
                );
            }
            echo ($errorHeader . $rsp . $errorFooter);
            return;
        }
        //------------ Private Request Header -------------//

        if ($vsb == 'private') {  // Private Request
            if (strpos(trim($uri, '/'), 'private/views') === 0) { // if request goes to view folder don't check the format
                if ( ! is_string($rsp) OR empty($rsp)) {
                    echo $hvc_view_error;
                    return;
                }
                return $rsp;
            }
            $rsp = json_decode($rsp, true); // Decode json to array

            if ( ! is_array($rsp)) { // If success not exists !
                if ($isXmlHttp) {
                    return array(
                        'success' => 0,
                        'message' => $hvc_error,
                        'errors' => array()
                    );
                }
                echo ($hvc_error);
                return;
            }
            //--------- Check the Private Response Format -----------//

            $key_errors = array_map(
                function ($val) {
                    return in_array($val, array('success','message','errors','results','e')); // Get the keys of hvc result array
                }, array_keys($rsp)
            );
            if (in_array(false, $key_errors, true)) {   // throws an exception
                if ($isXmlHttp) {
                    return array(
                        'success' => 0,
                        'message' => $hvc_error,
                        'errors' => array()
                    );
                }
                echo ($hvc_error);
                return;
            }

            if (isset($rsp['success']) AND $rsp['success'] == false AND (isset($rsp['e']) AND ! empty($rsp['e'])) AND (ENV == 'DEBUG' OR ENV == 'TEST')) {  // Show exceptional message to developers if environment not LIVE.
                $rsp['message'] = $rsp['e'];
            }
        }

        //------------ Private Request Header End -------------//

        if ($expiration === false) {  // delete cache after the request
            $this->deleteCache();
        }

        if (isset($rsp['results']) AND is_array($rsp['results'])) {
            $rsp['count'] = count($rsp['results']);
        }
        return $rsp;
    }

    // ------------------------------------------------------------------------

    /**
     * Execute Hvc Request
     *
     * @return   string
     */
    public function exec($expiration = null)
    {
        global $uri, $router, $logger;
        static $storage = array();      // store "$c " variables ( called controllers )
        // ------------------------------------------------------------------------

        $KEY = $this->getKey();   // Get Hvc Key
        $start = microtime(true); // Start the Query Timer 

        // ----------------- Static Php Cache -------------------//

        if (isset(self::$cid[$KEY])) {      // Cache the multiple HVC requests in the same controller. 
                                            // This cache type not related with Cache package.
            $response = $this->getResponse();
            $logger->debug('$_HVC: '.$this->getKey(), array('time' => number_format(microtime(true) - $start, 4), 'key' => $KEY, 'output' => '<br /><div style="float:left;">'.preg_replace('/[\r\n\t]+/', '', $response).'</div><div style="clear:both;"></div>'));
            $this->_clear();
            return $response;    // This is native system cache !
        }


        self::$cid[$KEY] = $KEY;    // store connection id.

        // ----------------- Memory Cache Control -------------------//

        if ($this->config['caching']) {
            $cache = $this->config['cache'](); 
            $cache = $cache::$driver;
            $response = $cache->get($KEY);
            if ( ! empty($response)) {              // If cache exists return to cached string.
                $logger->debug('$_HVC_CACHED: '.$uri->getUriString(), array('time' => number_format(microtime(true) - $start, 4), 'key' => $KEY, 'output' => '<br /><div style="float:left;">'.preg_replace('/[\r\n\t]+/', '', $response).'</div><div style="clear:both;"></div>'));
                $this->_clear();
                return base64_decode($response);    // encoding for specialchars
            }
        }

        // ----------------- Route is Valid -------------------//

        $response = $router->getResponse();

        if ($this->connection === false OR $response != false) {  // If router dispatch fail ?
            $rsp_k = key($response);
            $this->_clear();
            $this->setResponse($rsp_k . ' ' . $response[$rsp_k]);
            return $this->getResponse();
        }

        //----------------------------------------------------------------------------
        //  Create an uniq HVC Uri
        //  A Hvc uri must be unique otherwise
        //  may collission with standart uri, also we need it for caching feature.
        //  --------------------------------------------------------------------------

        $uri->setUriString(rtrim($uri->getUriString(), '/') . '/' .$KEY); // Create an uniq HVC Uri with md5 hash
        //  --------------------------------------------------------------------------

        $folder = PUBLIC_DIR;
        if ($this->getVisibility() == 'private') { // Send "private" requests to private folder
            $folder = PRIVATE_DIR;
        }

        $this->hvc_uri = "{$router->fetchDirectory()} / {$router->fetchClass()} / {$router->fetchMethod()}";
        $controller = $folder . $router->fetchDirectory() . DS . 'controller' . DS . $router->fetchClass() . EXT;

        // --------- Check class is exists in the storage ----------- //

        if (isset($storage[$this->hvc_uri])) {    // Check is multiple call to same class.
            $c = $storage[$this->hvc_uri];       // Get stored class.
        } else {
            require($controller);        // Call the controller.
        }

        // --------- End storage exists ----------- //
        // $c variable available here !

        if (!isset($c->request->global)) { // ** Let's create new request object for globals
            $c->request = new Request;       // create new request object;

            // create a global variable
            // keep all original objects in it.
            // e.g. $this->request->global->uri->getUriString();
            // ** Store global "Uri" and "Router" objects to make available them in sub layers
            //---------------------------------------------------------------------------

            $c->request->global = new stdClass;      // Create an empty class called "global"
            $c->request->global->uri = $this->uri;        // Let's assign the global uri object
            $c->request->global->router = $this->router;     // Let's assign the global uri object
        }

        // End store global variables

        if (strncmp($router->fetchMethod(), '_', 1) == 0 
            OR in_array(strtolower($router->fetchMethod()), array_map('strtolower', get_class_methods('Controller')))
        ) {
            $this->_clear();
            $this->setResponse('404 - Hvc request not found: ' . $this->hvc_uri);
            return $this->getResponse();
        }

        // Get application methods
        //----------------------------

        $storedMethods = array_keys($c->_controllerAppMethods);

        //----------------------------
        // Check method exist or not
        //----------------------------

        if ( ! in_array(strtolower($router->fetchMethod()), $storedMethods)) {
            $this->_clear();
            $this->setResponse('404 - Hvc request not found: ' . $this->hvc_uri);
            return $this->getResponse();
        }

        // Slice Arguments
        //----------------------------

        $arguments = array_slice($uri->rsegments, 2);

        //----------------------------

        ob_start(); // Start the output buffer.

        // Call the requested method. Any URI segments present (besides the directory / class / index / arguments ) 
        // will be passed to the method for convenience
            // directory = 0, class = 1,  ( arguments = 2) ( @deprecated  method = 2 method always = index )
        call_user_func_array(array($c, $router->fetchMethod()), $arguments);

        //----------------------------

        $content = ob_get_contents(); // Get the contents of the output buffer

        //----------------------------

        ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering

        //----------------------------

        $this->setResponse($content);
        $this->_clear();

        //--------------------------------------
        // Store classes to $storage container
        //--------------------------------------
        
        $storage[$this->hvc_uri] = $c; // Store class names to storage. We fetch it if its available in storage.

        //----------------------------
        // End storage

        $response = $this->getResponse();

        //------------- Set to Cache -------------//

        if (is_numeric($expiration) AND $this->config['caching']) {
            $cache = $this->config['cache']();   // load cache library
            $cache = $cache::$driver;
            $cache->set($KEY, base64_encode($response), (int)$expiration);
        }
        $logger->debug('$_HVC: '.$this->getUri(), array('time' => number_format(microtime(true) - $start, 4), 'key' => $KEY, 'output' => '<br /><div style="float:left;">'.preg_replace('/[\r\n\t]+/', '', $response).'</div><div style="clear:both;"></div>'));

        return $response;
    }

    // --------------------------------------------------------------------

    /**
     * Reset router for mutiple hvc requests
     * or who want to close the hvc connection.
     *
     * @return   void
     */
    protected function _clear()
    {
        if ( ! isset($_SERVER['HVC_REQUEST_URI'])) { // if no hvc header return to null;
            return;
        }

        // Assign global variables we copied before ..
        // --------------------------------------------------
        $_SERVER = array();  // Just reset server variable other wise  we don't 
                             // use global variables in hvc in hvc.

        // $_SERVER = $_POST = $_GET = $_REQUEST = array(); // reset all globals

        // $_GET = $GLOBALS['_GET_BACKUP'];   // Set back original request variables
        // $_POST = $GLOBALS['_POST_BACKUP'];
        $_SERVER = $GLOBALS['_SERVER_BACKUP'];
        // $_REQUEST = $GLOBALS['_REQUEST_BACKUP'];

        // Set original $this to controller instance that we backup before.
        // --------------------------------------------------

        getInstance($this->global);
        getInstance()->uri    = $this->uri;     // restore back original objects
        getInstance()->router = $this->router;

        $this->clear();  // reset all HVC variables.

        $this->process_done = true;  // This means hvc process done without any errors.
        // If process_done == false we say to destruct method "reset the router" variables 
        // and return to original variables of the Framework's before we clone them.
    }

    // --------------------------------------------------------------------

    /**
     * Set $_SERVER vars foreach hvc
     * requests.
     *
     * @param string $key
     * @param mixed  $val
     */
    public function setServer($key, $val)
    {
        $_SERVER[$key] = $val;
        $this->_setConnString($key . $val);
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Set hvc response.
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
     * Get none-decoded original Hvc
     * response.
     * 
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    // --------------------------------------------------------------------

    /**
     * Create HVC connection string next
     * we will convert it to connection id.
     *
     * @param    mixed $id
     */
    protected function _setConnString($id)
    {
        $this->conn_string .= $id;
    }

    // --------------------------------------------------------------------

    /**
     * Returns Hvc key.
     *
     * @return   string
     */
    public function getKey()
    {
        return self::KEY . hash('md5', trim($this->conn_string));
    }

    // --------------------------------------------------------------------

    /**
     * Delete the cache manually
     * 
     * @return string
     */
    public function deleteCache($key = '')
    {
        if (empty($key)) {          // if key not provided the get current hvc key
            $key = $this->getKey();
        }
        $cache = $this->config['cache'](); // load cache object
        $cache = $cache::$driver;
        if ($cache->keyExists($key)) {
            return $cache->delete($key);
        }
        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Get last hvc uri
     * 
     * @return string
     */
    public function getUri()
    {
        return $this->hvc_uri;
    }

    // --------------------------------------------------------------------

    /**
     * Close Hvc Connection
     * 
     * If we have any possible hvc exceptions
     * reset the router variables, complete to HVC process
     * and return to original vars.
     * 
     * @return void
     */
    public function __destruct()
    {
        if ($this->process_done == false) {
            $this->_clear();
            return;
        }
        $this->process_done = false;
    }

}

// END Hvc Class

/* End of file hvc.php */
/* Location: ./packages/hvc/releases/0.0.1/hvc.php */