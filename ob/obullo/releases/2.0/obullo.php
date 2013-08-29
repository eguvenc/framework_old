<?php
 
 /**
 * Framework Component
 * Version 2.0
 * 
 * @package         Obullo 
 * @subpackage      Obullo.core
 * @category        Core
 * @version         2.0
 */

Class Obullo
{
    /**
     * Run the application
     */
    public function run()
    {   
        $packages = getConfig('packages.cache'); // get cached package.json configuration file.
        require (OB_MODULES .'obullo'. DS .'releases'. DS .$packages['dependencies']['obullo']['version']. DS .'src'. DS .'common'. EXT);
        
        ####  Core packages ( log, error, bench, uri, router, config, input, output ) ####
        
        new log\start();
        new error\start();
        
        $uriClass = getComponent('uri');
        $uri      = $uriClass::getInstance();
        
        $routerClass = getComponent('router');
        $router = $routerClass::getInstance();

        new bench\start();
        
        $locale = getComponent('locale');
        $locale::getInstance();
        
        bench\mark('total_execution_time_start');
        bench\mark('loading_time_base_classes_start');
        
        $inputClass = getComponent('input');
        $inputClass::getInstance()->_sanitizeGlobals();  // Initalize to input filter. ( Sanitize must be above the GLOBALS !! )             

        $outputClass = getComponent('output');
        $output = $outputClass::getInstance();
        
        $configClass = getComponent('config');
        $config = $configClass::getInstance(); 

        if ($output->_displayCache($config, $uri) == true) { exit; }  // Check REQUEST uri if there is a Cached file exist 

        $page_uri   = "{$router->fetchDirectory()} / {$router->fetchClass()} / {$router->fetchMethod()}";
        $controller = MODULES .$router->fetchDirectory(). DS .'controller'. DS .$router->fetchClass(). EXT;

        bench\mark('loading_time_base_classes_end');  // Set a mark point for benchmarking  
        bench\mark('execution_time_( '.$page_uri.' )_start');  // Mark a start point so we can benchmark the controller 
       
        require ($controller);  // call the controller.

        if ( ! class_exists($router->fetchClass()) OR $router->fetchMethod() == 'controller' 
              OR $router->fetchMethod() == '_output'       // security fix.
              OR $router->fetchMethod() == '_ob_getInstance_'
              OR in_array(strtolower($router->fetchMethod()), array_map('strtolower', get_class_methods('Controller')))
            )
        {
            show404($page_uri);
        }
        
        $Class = $router->fetchClass();
        $ControllerClass = new $Class();           // If Everyting ok Declare Called Controller ! 

        if ( ! in_array(strtolower($router->fetchMethod()), array_map('strtolower', get_class_methods($ControllerClass))))  // Check method exist or not 
        {
            show404($page_uri);
        }

        $arguments = array_slice($ControllerClass->uri->rsegments, 3);

        //                                                                     0       1       2
        // Call the requested method. Any URI segments present (besides the directory/class/method) 
        // will be passed to the method for convenience
        call_user_func_array(array($ControllerClass, $router->fetchMethod()), $arguments);

        bench\mark('execution_time_( '.$page_uri.' )_end');  // Mark a benchmark end point 

        // Write Cache file if cache on ! and Send the final rendered output to the browser
        $output->_display();

        while (ob_get_level() > 0) // close all buffers.  
        {
            ob_end_flush();
        }
    }
}

// END obullo.php File

/* End of file obullo.php
/* Location: ./ob/obullo/releases/2.0/obullo.php */