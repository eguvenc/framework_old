<?php

/**
 * Pdo Connection Class.
 *
 * @package         Ob
 * @subpackage      database     
 * @category        databases
 * @link            
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
    * @param string $driver
    * @param string $options
    * @return object
    * @throws Exception 
    */
    public static function connect($driver = '', $options = array())
    {  
        $driver_name = '';
        switch (strtolower($driver))
        {   
           case ($driver == 'mysql'): // MySQL 3.x/4.x/5.x  
           $driver_name = 'mysql';
             break;
         
           case ($driver == 'ibm' || $driver == 'db2'):  // IBM - DB2 / Informix not yet ..
           $driver_name = 'ibm';
             break;
         
           case ($driver == 'dblib' || $driver == 'mssql' || $driver == 'freetds' || $driver == 'sybase'):            // MSSQL / DBLIB / FREETDS / SYBASE
           $driver_name = 'mssql';
             break;
          
           case ($driver == 'oci' || $driver == 'oracle'):            // OCI (ORACLE)
           $driver_name = 'oci';
             break;
           
           case ($driver == 'odbc'): // ODBC
           $driver_name = 'odbc';
             break;

           case ($driver == 'pgsql'): // PostgreSQL
           $driver_name = 'pgsql';
             break;
             
           case ($driver == 'sqlite' || $driver == 'sqlite2' || $driver == 'sqlite3'):   // SQLITE
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
            throw new Exception('The Database pdo library does not support: '. $driver); 
        }
        
        if ( ! in_array($driver, PDO::getAvailableDrivers()))  // check the PDO driver is available
        {
            throw new Exception('The PDO' . $driver . ' driver is not currently installed on your server.');
        }
        
        $classname = 'Pdo_'.ucfirst($driver_name);
        $DB = new $classname($options);
        $DB->_connect();
        
        return $DB->getConnection();
    }
    
}

/* End of file database_pdo.php */
/* Location: ./ob/database_pdo/releases/0.0.1/database_pdo.php */