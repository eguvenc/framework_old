<?php

/**
 * Exceptions Class
 *
 * @package       Obullo
 * @subpackage    exceptions
 * @category      Exceptions
 * @link
 */
Class Exceptions {

    function __construct()
    {
        \log\me('debug', "Exceptions Class Initialized");
    }
 
    /**
    * Display all errors
    * 
    * @param object $e
    * @param string $type
    * 
    * @return string
    */
    public function write($e, $type = '')
    {
        $type = ($type != '') ? ucwords(strtolower($type)) : 'Exception Error';
        
        // If user want to close error_reporting in some parts of the application.
        //-----------------------------------------------------------------------
        
        if(config('error_reporting') == '0')
        {
            \log\me('debug', 'Error reporting seems Off, check the config.php file $config[\'error_reporting\'].');
            
            return;
        }

        // Database Errors
        //-----------------------------------------------------------------------
        
        $code = $e->getCode();
        $last_query = '';
        if(isset(getInstance()->db))
        {
            $prepare    = (isset(getInstance()->db->prepare)) ? getInstance()->db->prepare : false;
            $last_query = getInstance()->db->lastQuery($prepare);
        }
        
        if( ! empty($last_query))
        {
            $type = 'Database';
            $code = 'SQL';  // We understand this is a db error.
            $data['sql'] = $last_query;
        }
        
        // Command Line Errors
        //-----------------------------------------------------------------------
        
        if(defined('STDIN'))  // If Command Line Request. 
        {
            echo $type .': '. $e->getMessage(). ' File: ' .\error\securePath($e->getFile()). ' Line: '. $e->getLine(). "\n";
            
            $cmd_type = (defined('TASK')) ? 'Task' : 'Cmd';
            
            \log\me('error', '('.$cmd_type.') '.$type.': '.$e->getMessage(). ' '.\error\securePath($e->getFile()).' '.$e->getLine(), true); 
            
            return;
        }
                
        // Load Error Template
        //-----------------------------------------------------------------------
        
        $data['e']    = $e;
        $data['type'] = $type;

        ob_start();
        
        include (APP .'errors'. DS .'exception'.EXT);
        
        $error_msg = ob_get_clean();
        
        // Log Php Errors
        //-----------------------------------------------------------------------
        
        \log\me('error', $type.': '.$e->getMessage(). ' '.\error\securePath($e->getFile()).' '.$e->getLine(), true); 
             
        // Displaying Errors
        //-----------------------------------------------------------------------            
        
        $level  = config('error_reporting');

        if(is_numeric($level)) 
        {
            switch ($level) 
            {               
               case  0: return; break; 
               case  1: echo $error_msg; return; break;
            }   
        }       
    
        $rules = \error\parseRegex($level);
        
        if($rules == false) 
        {
            return;
        }
        
        $allowed_errors = \error\getAllowedErrors($rules);  // Check displaying error enabled for current error.
    
        if(isset($allowed_errors[$code]))
        {
            echo $error_msg; 
        }
    }
    
}
/* End of file Exceptions.php */
/* Location: ./ob/releases/0.0.1/exceptions.php */