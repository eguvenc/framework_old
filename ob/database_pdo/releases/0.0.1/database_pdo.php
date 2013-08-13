<?php
namespace Ob\Database_Pdo;

/**
 * Pdo Connection Class.
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
            throw new \Exception('The PDO extension is required but extension is not loaded.');
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
    public static function connect($dbdriver = '', $options = array())
    {  
        $driver_name = '';
        switch (strtolower($dbdriver))
        {   
           case ($dbdriver == 'mysql'): // MySQL 3.x/4.x/5.x  
           $driver_name = 'mysql';
             break;
         
           case ($dbdriver == 'ibm' || $dbdriver == 'db2'):  // IBM - DB2 / Informix not yet ..
           $driver_name = 'ibm';
             break;
         
           case ($dbdriver == 'dblib' || $dbdriver == 'mssql' || $dbdriver == 'freetds' || $dbdriver == 'sybase'):            // MSSQL / DBLIB / FREETDS / SYBASE
           $driver_name = 'mssql';
             break;
          
           case ($dbdriver == 'oci' || $dbdriver == 'oracle'):            // OCI (ORACLE)
           $driver_name = 'oci';
             break;
           
           case ($dbdriver == 'odbc'): // ODBC
           $driver_name = 'odbc';
             break;

           case ($dbdriver == 'pgsql'): // PostgreSQL
           $driver_name = 'pgsql';
             break;
             
           case ($dbdriver == 'sqlite' || $dbdriver == 'sqlite2' || $dbdriver == 'sqlite3'):   // SQLITE
           $driver_name = 'sqlite';
             break;
             
           case 'firebird':            // Firebird
           $driver_name = 'firebird';
             break;
            
           case '4d':            // 4D
           $driver_name = '4d';
             break;
           
           case 'cubrid':            // CUBRID  
           $driver_name = 'cubrid';
             break;
        }

        if($driver_name == '')
        {
            throw new \Exception('The Database pdo library does not support: '. $dbdriver); 
        }
        
        if ( ! in_array($dbdriver, \PDO::getAvailableDrivers()))  // check the PDO driver is available
        {
            throw new \Exception('The PDO' . $dbdriver . ' driver is not currently installed on your server.');
        }
        
        $classname = '\Ob\Pdo_'.ucfirst($driver_name).'\Pdo_'.ucfirst($driver_name);
        $DB = new $classname($options);
        $DB->__wakeup();
        
        return $DB->getConnection();
    }
    
}

/* End of file database_pdo.php */
/* Location: ./ob/database_pdo/releases/0.0.1/database_pdo.php */