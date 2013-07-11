<?php

$packages = get_config('packages');

require (OB_MODULES .'database_pdo'. DS .'releases'. DS .$packages['dependencies']['database_pdo']['version']. DS .'src'. DS .'pdo_database_adapter'. EXT);

// ------------------------------------------------------------------------

/**
 * Obullo Database Connection Class.
 *
 * @package         Obullo 
 * @subpackage      Obullo.database     
 * @category        Database
 * @version         0.1
 * 
 */
Class Database_PDO {
    
    /**
    * Constructor
    */
    function __construct()
    {
        if ( ! extension_loaded('pdo') )
        {
            throw new Exception('The PDO extension is required but extension is not loaded.');
        }
    }
    
    /**
    * Connect Requested PDO Driver
    * 
    * @param    mixed   $param database parameters
    * @param    string  $db_var database variable
    * @return   object of PDO Instance.
    */
    public static function connect($db_var = 'db', $param = '')
    {                                      
        $dbdriver = is_array($param) ? $param['dbdriver'] : db_item('dbdriver', $db_var); 
        $driver_name = '';
                     
        switch (strtolower($dbdriver))
        {   
           // MySQL 3.x/4.x/5.x  
           case ($dbdriver == 'mysql'): 
           $driver_name = 'mysql';
             break;
             
           // IBM - DB2 / Informix not yet ..
           case ($dbdriver == 'ibm' || $dbdriver == 'db2'):
           $driver_name = 'ibm';
             break;
             
           // MSSQL / DBLIB / FREETDS / SYBASE
           case ($dbdriver == 'dblib' || $dbdriver == 'mssql' || $dbdriver == 'freetds' || $dbdriver == 'sybase'):
           $driver_name = 'mssql';
             break;
           
           // OCI (ORACLE)
           case ($dbdriver == 'oci' || $dbdriver == 'oracle'):
           $driver_name = 'oci';
             break;
             
           // ODBC
           case ($dbdriver == 'odbc'):
           $driver_name = 'odbc';
             break;
             
           // PGSQL
           case ($dbdriver == 'pgsql'):
           $driver_name = 'pgsql';
             break;
             
           // SQLITE
           case ($dbdriver == 'sqlite' || $dbdriver == 'sqlite2' || $dbdriver == 'sqlite3'):
           $driver_name = 'sqlite';
             break;
             
           // Firebird
           case 'firebird':
           $driver_name = 'firebird';
             break;
             
           // 4D
           case '4d':
           $driver_name = '4d';
             break;
         
           // CUBRID    
           case 'cubrid':
           $driver_name = 'cubrid';
             break;
         
           // MONGO   
           case 'mongodb':
           $driver_name = 'mongodb';
             break;
           
          default:
          throw new Exception('Obullo database library does not support: '. $dbdriver); 
           
        } // end switch.
        
        $hostname = db_item('hostname', $db_var);
        
        if($hostname == FALSE)
        {
            throw new Exception('The ' . $db_var . ' database configuration undefined in your config/database.php file !');
        }
        
        //----------- MONGO PACKAGE SUPPORT ------------//
        
        if($driver_name == 'mongodb') 
        {
            $mongo = new Mongo_DB();
            
            return $mongo->connect();
        }
        
        //----------- MONGO PACKAGE SUPPORT END ------------//
        
        if ( ! in_array($dbdriver, PDO::getAvailableDrivers()))  // check the PDO driver is available
        {
            throw new Exception('The PDO' . $dbdriver . ' driver is not currently installed on your server !');
        }
        
        if(is_array($param))
        {
            $options = array_merge($param, array('default_db' => $db_var));
        }
        else
        {
            $options = array('default_db' => $db_var);
        }
        
        $db_classname = 'Pdo_'.ucfirst($driver_name);
        
        $DB = new $db_classname($options);
        $DB->__wakeup();
        
        return $DB->get_connection();
    }
    
}

/* End of file database_pdo.php */
/* Location: ./ob/database_pdo/releases/0.0.1/database_pdo.php */