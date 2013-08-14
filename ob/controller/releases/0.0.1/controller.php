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
    public $config,$router,$uri,$output,$locale;
    
    public function __construct()       
    {   
        self::$instance = &$this;
        
        // Default Loaded Core Libraries
        // ------------------------------------
        
        $this->config = Config\Config::getInstance();
        $this->router = Router\Router::getInstance();
        $this->uri    = Uri\Uri::getInstance();
        $this->output = Output\Output::getInstance();
        $this->locale = Locale\Locale::getInstance();
        
        // Initialize to Autoloaders
        // ------------------------------------
        
        $autoload = getStatic('autoload', '', APP .'config');
        
        log\me('debug', 'Application Autoload Initialized');

        if(is_array($autoload))
        {
            foreach(array_keys($autoload) as $key)
            {
                if(count($autoload[$key]) > 0)
                {                    
                    foreach($autoload[$key] as $filename)
                    {
                        $class = $filename;
                        
                        if($key == 'helper')
                        {
                            $class = $class.'\start';
                            new $class();
                        } 
                        
                        if($key == 'library')
                        {
                            $classname = ucfirst($filename).'\\'.ucfirst($filename);
                            new $classname();
                        }
                        
                        if($key == 'config')
                        {
                            $this->config->load($filename);
                        }
                        
                        if($key == 'locale')
                        {
                            $this->locale->load($filename);
                        }
                    }
                }
            }
        }

        // Initialize to Autorun
        // ------------------------------------
        
        $autorun = getStatic('autorun', '', APP .'config');
        
        log\me('debug', 'Application Autorun Initialized');

        if(isset($autorun['function']))
        {
            if(count($autorun['function']) > 0)
            {
                foreach(array_reverse($autorun['function']) as $function => $arguments)
                {
                    if( ! function_exists($function))
                    {
                        throw new \Exception('The autorun function '. $function . ' not found, please define it in app/config/autoload.php');
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
    public static function _ob_getInstance_($new_instance = '')
    {   
        if(is_object($new_instance))
        {
            self::$instance = $new_instance;
        }    

        return self::$instance;
    } 
    
}

// END Controller Class

/* End of file controller.php */
/* Location: ./ob/controller/releases/0.1/controller.php */