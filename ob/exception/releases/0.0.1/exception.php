<?php
namespace Ob\Exception;


/**
 * Exceptions Class
 *
 * @package       Obullo
 * @subpackage    exceptions
 * @category      Exceptions
 * @link
 */
Class Exception {

    function __construct()
    {
        \Ob\log\me('debug', "Exception Class Initialized");
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
        
        if(\Ob\config('error_reporting') == '0')
        {
            \Ob\log\me('debug', 'Error reporting seems Off, check the config.php file $config[\'error_reporting\'].');
            
            return;
        }

        // Database Errors
        //-----------------------------------------------------------------------
        
        $code = $e->getCode();
        $last_query = '';
        if(isset(getInstance()->db))
        {
            $prepare    = (isset(getInstance()->db->prepare)) ? getInstance()->db->prepare : false;
            $last_query = getInstance()->db->last_query($prepare);
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
            echo $type .': '. $e->getMessage(). ' File: ' .\Ob\error\secure_path($e->getFile()). ' Line: '. $e->getLine(). "\n";
            
            $cmd_type = (defined('TASK')) ? 'Task' : 'Cmd';
            
            \Ob\log\me('error', '('.$cmd_type.') '.$type.': '.$e->getMessage(). ' '.\Ob\error\secure_path($e->getFile()).' '.$e->getLine(), TRUE); 
            
            return;
        }
                
        // Load Error Template
        //-----------------------------------------------------------------------
        
        $data['e']    = $e;
        $data['type'] = $type;

        ob_start();
        
        include (APP .'errors'. DS .'ob_exception'.EXT);
        
        $error_msg = ob_get_clean();
        
        // Log Php Errors
        //-----------------------------------------------------------------------
        
        \Ob\log\me('error', $type.': '.$e->getMessage(). ' '.\Ob\error\secure_path($e->getFile()).' '.$e->getLine(), true); 
             
        // Displaying Errors
        //-----------------------------------------------------------------------            
        
        $level  = \Ob\config('error_reporting');

        if(is_numeric($level)) 
        {
            switch ($level) 
            {               
               case  0: return; break; 
               case  1: echo $error_msg; return; break;
            }   
        }       
    
        $rules = \Ob\error\parse_regex($level);
        
        if($rules == FALSE) 
        {
            return;
        }
        
        $allowed_errors = \Ob\error\get_allowed_errors($rules);  // Check displaying error enabled for current error.
    
        if(isset($allowed_errors[$code]))
        {
            echo $error_msg; 
        }
    }
    
}
/* End of file Exceptions.php */
/* Location: ./ob/releases/0.0.1/exceptions.php */