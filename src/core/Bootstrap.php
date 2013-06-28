<?php

/**
 * Obullo Framework (c) 2009 - 2012.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo     
 * @author          obullo.com
 * @copyright       Obullo Team
 * @since           Version 1.0
 * @filesource
 * @license
 */
 
 /**
 * Obullo Bootstrap file.
 * Control Your Application Boot
 * 
 * @package         Obullo 
 * @subpackage      Obullo.core
 * @category        Front Controller
 * @version         1.0
 */

//  Include application header files.
// -------------------------------------------------------------------- 
if( ! function_exists('ob_include_files'))
{
    function ob_include_files()
    {
        require (APP  .'config'. DS .'constants'. EXT);  // Your constants ..
        require (BASE .'file_constants'. EXT);
        require (BASE .'core'. DS .'Registry'. EXT);
        require (BASE .'core'. DS .'Common'. EXT);
        require (BASE .'core'. DS .'Loader'. EXT);
        
        $packages = get_config('packages');
        require (OB_MODULES .'log'. DS .'releases'. DS .$packages['dependencies']['log']. DS .'log'. EXT); 
    }
}

//  Include header functions. 
// -------------------------------------------------------------------- 
if( ! function_exists('ob_set_headers'))
{
    function ob_set_headers()
    {   
        if ( ! is_php('5.3')) // Kill magic quotes 
        {
            @set_magic_quotes_runtime(0); 
        }   
        
        $packages = get_config('packages');
        require (OB_MODULES .'log'. DS .'releases'. DS .$packages['dependencies']['log']. DS .'log'. EXT); 
        
        ###  load core libraries ####
        
        lib('ob/Uri');
        lib('ob/Router');
        lib('ob/Lang');
        lib('ob/Benchmark');
        lib('ob/Input');
        
        ###  load core helpers ####

        require (OB_MODULES .'error'. DS .'releases'. DS .$packages['dependencies']['error']. DS .'error'. EXT); 
        require (OB_MODULES .'input'. DS .'releases'. DS .$packages['dependencies']['input']. DS .'input'. EXT); 
    }
}

//  Run the application.
// --------------------------------------------------------------------    
if( ! function_exists('ob_system_run'))
{
    function ob_system_run()
    { 
        $uri    = lib('ob/Uri'); 
        $router = lib('ob/Router');
        
        benchmark_mark('total_execution_time_start');
        benchmark_mark('loading_time_base_classes_start');
        
        lib('ob/Input')->_sanitize_globals();  // Initalize to input filter. ( Sanitize must be above the GLOBALS !! )             

        $output = lib('ob/Output');
        $config = lib('ob/Config'); 
                
        if ($output->_display_cache($config, $uri, $router) == TRUE) { exit; }  // Check REQUEST uri if there is a Cached file exist 
        
        $folder = 'controllers';
        
        if(defined('STDIN'))  // Command Line Request
        {                
            if($router->fetch_directory() != 'tasks')    // Check module and application folders.
            {                    
                if(is_dir(MODULES .$router->fetch_directory(). DS .'tasks'))
                {
                    $folder = 'tasks';
                } 
            }
        }
        
        $page_uri   = "{$router->fetch_directory()} / {$router->fetch_class()} / {$router->fetch_method()}";
        $controller = MODULES .$router->fetch_directory(). DS .$folder. DS .$router->fetch_class(). EXT;

        require (BASE .'core'. DS .'Controller'. EXT);  // We load Model File with a 'ob_autoload' function which is
                                                        // located in obullo/core/common.php.         
        benchmark_mark('loading_time_base_classes_end');  // Set a mark point for benchmarking  
        benchmark_mark('execution_time_( '.$page_uri.' )_start');  // Mark a start point so we can benchmark the controller 
        
        require ($controller);  // call the controller.
        
        if ( ! class_exists($router->fetch_class()) OR $router->fetch_method() == 'controller' 
              OR $router->fetch_method() == '_output'       // security fix.
              OR $router->fetch_method() == '_hmvc_output'
              OR $router->fetch_method() == '_instance'
              OR in_array(strtolower($router->fetch_method()), array_map('strtolower', get_class_methods('Controller')))
            )
        {
            show_404($page_uri);
        }
        
        $Class = $router->fetch_class();
        
        $OB = new $Class();           // If Everyting ok Declare Called Controller ! 

        if ( ! in_array(strtolower($router->fetch_method()), array_map('strtolower', get_class_methods($OB))))  // Check method exist or not 
        {
            show_404($page_uri);
        }
        
        $arguments = array_slice($OB->uri->rsegments, 3);
        
        //                                                                     0       1       2
        // Call the requested method. Any URI segments present (besides the directory/class/method) 
        // will be passed to the method for convenience
        call_user_func_array(array($OB, $router->fetch_method()), $arguments);
        
        benchmark_mark('execution_time_( '.$page_uri.' )_end');  // Mark a benchmark end point 
        
        // Write Cache file if cache on ! and Send the final rendered output to the browser
        $output->_display();
            
        while (ob_get_level() > 0) // close all buffers.  
        { 
            ob_end_flush();    
        }
        
    }
}

// Close the connections.
// --------------------------------------------------------------------  
if( ! function_exists('ob_system_close'))
{
    function ob_system_close()
    {
        foreach(loader::$_databases as $db_var)  // Close all PDO connections..  
        {   
            $driver = db_item('dbdriver', $db_var);
            
            if($driver == 'mongodb' AND is_object(this()->{$db_var}->connection))
            {
                this()->{$db_var}->connection->close();
            } 
            else 
            {
                this()->{$db_var} = NULL;
            }
        } 
    }
}

// END Bootstrap.php File

/* End of file Bootstrap.php
/* Location: ./obullo/core/Bootstrap.php */