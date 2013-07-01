<?php
 
 /**
 * Obullo Bootstrap file.
 * Control Your Application Boot
 * 
 * @package         Obullo 
 * @subpackage      Obullo.core
 * @category        Bootstrap
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
        require (BASE .'core'. DS .'Common'. EXT);
        require (BASE .'core'. DS .'Loader'. EXT);
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

        loader::helper('ob/log');
        loader::helper('ob/error'); 

        ###  load core libraries ####
                
        $packages = get_config('packages');
        
        if( ! isset($packages['dependencies']['exceptions'])){ exit('Exceptions package not installed, please check your package.json'); }
        if( ! isset($packages['dependencies']['uri'])){ exit('Uri package not installed, please check your package.json'); }
        if( ! isset($packages['dependencies']['config'])){ exit('Config package not installed, please check your package.json'); }
        if( ! isset($packages['dependencies']['output'])){ exit('Output package not installed, please check your package.json'); }
        if( ! isset($packages['dependencies']['router'])){ exit('Router package not installed, please check your package.json'); }
        if( ! isset($packages['dependencies']['locale'])){ exit('Locale package not installed, please check your package.json'); }
        if( ! isset($packages['dependencies']['benchmark'])){ exit('Benchmark package not installed, please check your package.json'); }
        if( ! isset($packages['dependencies']['input'])){ exit('Input package not installed, please check your package.json'); }
        
        Uri::getInstance();
        Router::getInstance();
        Locale::getInstance();
        Benchmark::getInstance();
        Input::getInstance();
     
        ###  load core helpers ####

    }
}

//  Run the application.
// --------------------------------------------------------------------    
if( ! function_exists('ob_system_run'))
{
    function ob_system_run()
    { 
        $uri    = Uri::getInstance(); 
        $router = Router::getInstance();
        
        benchmark_mark('total_execution_time_start');
        benchmark_mark('loading_time_base_classes_start');
        
        Input::getInstance()->_sanitize_globals();  // Initalize to input filter. ( Sanitize must be above the GLOBALS !! )             

        $output = Output::getInstance();
        $config = Config::getInstance(); 
                
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