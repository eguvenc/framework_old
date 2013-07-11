<?php
 
 /**
 * Controller Class.
 *
 * Main Controller class.
 *
 * @package         Obullo 
 * @subpackage      Obullo.core     
 * @category        Core
 * @version         0.2
 */

Class Controller {

    private static $instance;

    public function __construct()       
    {   
        self::$instance = &$this;

        // Load Obullo Core Libraries
        // ------------------------------------
      
        $this->config = Config::getInstance();
        $this->router = Router::getInstance();
        $this->uri    = Uri::getInstance();
        $this->output = Output::getInstance();
        $this->locale = Locale::getInstance();
        
        // Initialize to Autoloaders
        // ------------------------------------
        
        $autoload = get_static('autoload', '', APP .'config');
        
        log_me('debug', 'Application Autoload Initialized');

        if(is_array($autoload))
        {
            foreach(array_keys($autoload) as $key)
            {
                if(count($autoload[$key]) > 0)
                {                    
                    foreach($autoload[$key] as $filename)
                    {
                        loader::$key($filename);
                    }
                }
            }
        }

        // Initialize to Autorun
        // ------------------------------------
        
        $autorun = get_static('autorun', '', APP .'config');
        
        log_me('debug', 'Application Autorun Initialized');

        if(isset($autorun['function']))
        {
            if(count($autorun['function']) > 0)
            {
                foreach(array_reverse($autorun['function']) as $function => $arguments)
                {
                    if( ! function_exists($function))
                    {
                        throw new Exception('The autorun function '. $function . ' not found, please define it in APP/config/autoload.php');
                    }

                    call_user_func_array($function, $arguments);   // Run autorun function.
                }
            }
        }  
        
        // ------------------------------------
    }

    // -------------------------------------------------------------------- 
    
    /**
    * Fetch or Set Controller Instance
    * 
    * @param type $new_instance
    * @return type 
    */
    public static function __obullo_instance($new_instance = '')
    {   
        if(is_object($new_instance))
        {
            self::$instance = $new_instance;
        }    

        return self::$instance;
    } 
    
}

// -------------------------------------------------------------------- 

/**
* Grab Obullo Super Object
*
* @param object $new_istance  
*/
function getInstance($new_instance = '') 
{ 
    if(is_object($new_instance))  // fixed HMVC object type of integer bug in php 5.1.6
    {
        Controller::__obullo_instance($new_instance);
    }
    
    return Controller::__obullo_instance(); 
}

// END Controller Class

/* End of file controller.php */
/* Location: ./ob/obullo/releases/2.0/src/controller.php */