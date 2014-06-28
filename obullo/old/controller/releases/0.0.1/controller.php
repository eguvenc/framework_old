<?php

/**
 * Controller Class.
 *
 * Main Controller class.
 *
 * @package       packages
 * @subpackage    controller     
 * @category      controllers
 * @link
 */
Class Controller
{
    public static $instance;                        // Controller instance
    public $_controllerAppMethods = array();  // Controller user defined methods. ( @private )
    public $_controllerAppPublicMethods = array();  // Controller user defined methods. ( @private )
    public $config, $uri, $router, $translator, $response, $logger;  // Default packages

    // ------------------------------------------------------------------------

    /**
     * Closure function for 
     * construction
     * 
     * @param null $closure object or null
     */
    public function __construct($closure = null)
    {
        global $cfg, $uri, $router, $translator, $response, $logger;

        self::$instance = &$this;

        // Assign Default Loaded Packages
        // ------------------------------------
        // NOTICE:
        $this->config     = &$cfg;              // If we don't use assign by reference this will cause some errors in Hvc.
        $this->uri        = &$uri;              // The bug is insteresting, when we work with multiple page not found requests
        $this->router     = &$router;           // The objects of getInstance() keep the last instances of the last request.
        $this->translator = &$translator;       // that means the instance don't do the reset. Keep in your mind we need use pass by reference
        $this->response   = &$response;         // for variables.
        $this->logger     = &$logger;           // @see http://www.php.net/manual/en/language.references.whatdo.php

        // Run Construct Method
        // ------------------------------------

        if (is_callable($closure)) {
            call_user_func_array(Closure::bind($closure, $this, get_class()), array());
        }
    }

    // ------------------------------------------------------------------------

    /**
     * We prevent custom variables
     *
     * this is not allowed $this->user_variable = 'this is disgusting'
     * in controller
     * 
     * @param string $key
     * @param string $val
     */
    public function __set($key, $val)  // Custom variables is not allowed !!! 
    {
        if (!is_object($val) AND $key != '_controllerAppMethods' AND $key != '_controllerAppPublicMethods') {
            throw new Exception('Manually storing variables into Controller is not allowed');
        }

        $this->{$key} = $val; // store only application classes & packages 
        // and any variable which type is object
    }

    // ------------------------------------------------------------------------

    /**
     * Create the controller methods.
     * 
     * @param  string $methodName  
     * @param  closure $methodCallable
     * @return void
     */
    public function func($methodName, $methodCallable)
    {
        $method = strtolower($methodName);
        $hooks  = explode('.', $methodName);

        $method = $hooks[0];
        if (isset($hooks[1])) {  // Run Controler Hooks
            unset($hooks[0]);
            foreach ($hooks as $class) {
                new $class;
            }
        }

        //-----------------------------------------------------
        // "One Public Method Per Controller" Rule
        //-----------------------------------------------------
        // if it is not a private method check the "One Public Method Per Controller" rule

        if (strncmp($methodName, '_', 1) !== 0 AND strpos($methodName, 'callback_') !== 0) {
            $this->_controllerAppPublicMethods[$method] = $methodName;

            if (sizeof($this->_controllerAppPublicMethods) > 1) {
                throw new Exception('Just one public method allowed, framework has a principle "One Public Method Per Controller". If you want to add private methods use underscore ( _methodname ). <pre>$c->func(\'_methodname\', function(){});</pre>');
            }
        }

        if ( ! is_callable($methodCallable)) {
            throw new InvalidArgumentException('Controller error: Second param must be callable.');
        }

        $this->_controllerAppMethods[$method] = Closure::bind($methodCallable, $this, get_class());
    }

    // ------------------------------------------------------------------------

    /**
     * Call the controller method
     * 
     * @param  string $methodName method
     * @param  array $args  closure function arguments
     * @return void
     */
    public function __call($method, $args)
    {
        if (isset($this->_controllerAppMethods[$method])) {
            return call_user_func_array($this->_controllerAppMethods[$method], $args);
        }

        throw new Exception(get_class() . ' error: There is no method "' . $method . '()" to call.');
    }

}

// END Controller Class

/* End of file controller.php */
/* Location: ./packages/controller/releases/0.1/controller.php */