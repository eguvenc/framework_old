<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009 - 2012.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @copyright       Obullo Team (c) 2009.
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Exception Class
 *
 * @package       Obullo
 * @subpackage    Libraries
 * @category      Exceptions
 * @link
 */
Class OB_Exception {

    function __construct()
    {
        log_me('debug', "Exception Class Initialized");
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
        $sql  = array();
        
        // If user want to close error_reporting in some parts of the application.
        //-----------------------------------------------------------------------
        
        if(lib('ob/Config')->item('error_reporting') == '0')
        {
            log_me('debug', 'Error reporting seems Off, check the config.php file $config[\'error_reporting\'].');
            
            return;
        }

        // Database Errors
        //-----------------------------------------------------------------------
        
        $code = $e->getCode();
        
        if(substr($e->getMessage(),0,3) == 'SQL') 
        {
            $ob   = this();
            $type = 'Database';
            $code = 'SQL';  // We understand this is an db error.
            
            foreach(loader::$_databases as $db_name => $db_var)
            {
               if(isset($ob->$db_var) AND is_object($ob->$db_var))
               {
                   $last_query = $ob->{$db_var}->last_query($ob->{$db_var}->prepare);
                   
                   if( ! empty($last_query))
                   {
                       $sql[$db_name] = $last_query;
                   }
               }
            }        
        }
        
        // Command Line Errors
        //-----------------------------------------------------------------------
        if(defined('CMD'))  // If Command Line Request. 
        {
            echo $type .': '. $e->getMessage(). ' File: ' .error_secure_path($e->getFile()). ' Line: '. $e->getLine(). "\n";
            
            $cmd_type = (defined('TASK')) ? 'Task' : 'Cmd';
            
            log_me('error', '('.$cmd_type.') '.$type.': '.$e->getMessage(). ' '.error_secure_path($e->getFile()).' '.$e->getLine(), TRUE); 
            
            return;
        }
                
        // Load Error Template
        //-----------------------------------------------------------------------
        loader::helper('ob/view');
        
        $data['e']    = $e;
        $data['sql']  = $sql;
        $data['type'] = $type;

        $error_msg = lib('ob/View')->load(APP .'core'. DS .'errors'. DS, 'ob_exception', $data, true);
        
        // Log Php Errors
        //-----------------------------------------------------------------------
        log_me('error', $type.': '.$e->getMessage(). ' '.error_secure_path($e->getFile()).' '.$e->getLine(), true); 
             
        // Displaying Errors
        //-----------------------------------------------------------------------                
        $level  = config('error_reporting');
        // $errors = error_get_defined_errors();

        if(is_numeric($level)) 
        {
            switch ($level) 
            {               
               case  0: return; break; 
               case  1: echo $error_msg; return; break;
            }   
        }       
    
        $rules = error_parse_regex($level);
        
        if($rules == FALSE) 
        {
            return;
        }
        
        $allowed_errors = error_get_allowed_errors($rules);  // Check displaying error enabled for current error.
    
        if(isset($allowed_errors[$code]))
        {
            echo $error_msg; 
        }
    }
    
}
/* End of file Exception.php */
/* Location: ./obullo/libraries/Exception.php */