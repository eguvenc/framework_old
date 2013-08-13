<?php
 
 /**
 * Obullo Core
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
        $packages = getConfig('packages');
        
        require (APP  .'config'. DS .'constants'. EXT);  // app constants.
        require (OB_MODULES .'obullo'. DS .'releases'. DS .$packages['version']. DS .'src'. DS .'ob'. EXT);
        
        if(Ob\packageExists('log')) // check log package is installed.
        {
            new Ob\log\start();
        }
               
        if(Ob\packageExists('error')) // check error package is installed.
        {
            new Ob\error\start();
        }
        
        $uri    = Ob\Uri\Uri::getInstance(); 
        $router = Ob\Router\Router::getInstance();

        new Ob\bench\start();
        
        Ob\Locale\Locale::getInstance();
        
        Ob\bench\mark('total_execution_time_start');
        Ob\bench\mark('loading_time_base_classes_start');
        
        $input = Ob\Input\Input::getInstance();
        $input->_sanitizeGlobals();  // Initalize to input filter. ( Sanitize must be above the GLOBALS !! )             

        $output = Ob\Output\Output::getInstance();
        $config = Ob\Config\Config::getInstance(); 

        if ($output->_displayCache($config, $uri) == true) { exit; }  // Check REQUEST uri if there is a Cached file exist 

        $folder = 'controllers';

        if(defined('STDIN'))  // Command Line Request
        {                
            if($router->fetchDirectory() != 'tasks')    // Check module and application folders.
            {                    
                if(is_dir(MODULES .$router->fetchDirectory(). DS .'tasks'))
                {
                    $folder = 'tasks';
                } 
            }
        }

        $page_uri   = "{$router->fetchDirectory()} / {$router->fetchClass()} / {$router->fetchMethod()}";
        $controller = MODULES .$router->fetchDirectory(). DS .$folder. DS .$router->fetchClass(). EXT;

        Ob\bench\mark('loading_time_base_classes_end');  // Set a mark point for benchmarking  
        Ob\bench\mark('execution_time_( '.$page_uri.' )_start');  // Mark a start point so we can benchmark the controller 
        
        require ($controller);  // call the controller.

        if ( ! class_exists('\Ob\\'.$router->fetchClass()) OR $router->fetchMethod() == 'controller' 
              OR $router->fetchMethod() == '_output'       // security fix.
              OR $router->fetchMethod() == '_ob_getInstance_'
              OR in_array(strtolower($router->fetchMethod()), array_map('strtolower', get_class_methods('Ob\Controller')))
            )
        {
            Ob\show404($page_uri);
        }
        
        $Class = '\Ob\\'.$router->fetchClass();
        $OB = new $Class();           // If Everyting ok Declare Called Controller ! 

        if ( ! in_array(strtolower($router->fetchMethod()), array_map('strtolower', get_class_methods($OB))))  // Check method exist or not 
        {
            Ob\show404($page_uri);
        }

        $arguments = array_slice($OB->uri->rsegments, 3);

        //                                                                     0       1       2
        // Call the requested method. Any URI segments present (besides the directory/class/method) 
        // will be passed to the method for convenience
        call_user_func_array(array($OB, $router->fetchMethod()), $arguments);

        Ob\bench\mark('execution_time_( '.$page_uri.' )_end');  // Mark a benchmark end point 

        // Write Cache file if cache on ! and Send the final rendered output to the browser
        $output->_display();

        while (ob_get_level() > 0) // close all buffers.  
        {
            ob_end_flush();
        }
        
        // Close the Db connection.
        ##############

        $driver = Ob\db('dbdriver');
        
        if($driver == 'mongodb' AND isset(Ob\getInstance()->db->connection) AND is_object(Ob\getInstance()->db->connection))
        {
            Ob\getInstance()->db->connection->close();
        } 
        else
        {
            if(isset(Ob\getInstance()->db) AND is_object(Ob\getInstance()->db))
            {
                Ob\getInstance()->db = null;
            }
        }
    }
    
}

// END obullo.php File

/* End of file obullo.php
/* Location: ./ob/obullo/releases/2.0/obullo.php */