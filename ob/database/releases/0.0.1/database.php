<?php
namespace Ob;

/**
 * Database Connection Class.
 *
 * @package         Obullo 
 * @subpackage      Obullo.database     
 * @category        Database
 * @version         0.1
 * 
 */
Class Database {
    
    /**
    * Constructor
    */
    function __construct($db_var = 'db', $params = '')
    {
        if($db_var === FALSE) // no instantiate.
        {
            return $this;
        }
        
        getInstance()->{$db_var} = $this->connect($db_var, $params);
        
        log\me('debug', 'Database Class Initialized.');
    }
    
    /**
    * Connect to Database
    * 
    * @param    mixed   $param database parameters
    * @param    string  $db_var database variable
    * @return   object of PDO Instance.
    */
    public function connect($db_var = 'db', $params = '')
    {                                      
        $dbdriver = is_array($params) ? $params['dbdriver'] : db_item('dbdriver', $db_var); 
        $hostname = db_item('hostname', $db_var);
        
        if(is_array($params))
        {
            $options = array_merge($params, array('default_db' => $db_var));
        }
        else
        {
            $options = array('default_db' => $db_var);
        }
        
        if($hostname == FALSE)
        {
            throw new Exception('The ' . $db_var . ' database configuration undefined in your config/database.php file.');
        }
        
        //----------- MONGO PACKAGE SUPPORT ------------//
        
        if(strtolower($dbdriver) == 'mongodb') 
        {
            $mongo = new Mongo_DB();
            
            return $mongo->connect();
        }
        
        //----------- MONGO PACKAGE SUPPORT END ------------//

        $packages = get_config('packages');
        
        if($packages['db_layer'] == 'Database_Pdo')
        {
            $database = new Database_Pdo();
            return $database->connect();
        }
        
        return FALSE;        
    }

}

/* End of file database.php */
/* Location: ./ob/database/releases/0.0.1/database.php */