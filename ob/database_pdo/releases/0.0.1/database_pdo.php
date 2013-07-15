<?php
namespace Ob;

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
Class Database_Pdo {
    
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
    * Connect to Pdo Driver
     * 
    * @param string $dbdriver
    * @param string $options
    * @return object
    * @throws Exception 
    */
    public static function connect($dbdriver = 'mysql', $options = array())
    {                                      
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
           
          default:
          throw new Exception('The Database pdo library does not support: '. $dbdriver); 
           
        }

        if ( ! in_array($dbdriver, \PDO::getAvailableDrivers()))  // check the PDO driver is available
        {
            throw new Exception('The PDO' . $dbdriver . ' driver is not currently installed on your server !');
        }
        
        $db_classname = 'Pdo_'.ucfirst($driver_name);
        $DB = new $db_classname($options);
        $DB->__wakeup();
        
        return $DB->get_connection();
    }
    
}

/* End of file database_pdo.php */
/* Location: ./ob/database_pdo/releases/0.0.1/database_pdo.php */