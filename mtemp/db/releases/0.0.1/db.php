<?php

/**
 * Database Connection Class.
 *
 * @package       packages 
 * @subpackage    db 
 * @category      database connection
 * @link            
 * 
 */

Class Db {
    
    /**
    * Constructor
    */
    function __construct($db_var = 'db', $params = '')
    {
        if(isset(getInstance()->{$db_var}) AND is_object(getInstance()->{$db_var}))
        {
           return;   // Lazy Loading.  
        }
        
        if($db_var !== false)
        {
           getInstance()->{$db_var} = $this->connect($db_var, $params); 
        }
        
        log\me('debug', 'Db Class Initialized.');
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
        if(isset(getInstance()->{$db_var}) AND is_object(getInstance()->{$db_var}))
        {
           return;   // Lazy Loading.  
        }
        
        $driver   = is_array($params) ? $params['driver'] : db('driver', $db_var); 
        $hostname = db('hostname', $db_var);
        
        if(is_array($params))
        {
            $options = array_merge($params, array('default_db' => $db_var));
        }
        else
        {
            $options = array('default_db' => $db_var);
        }
        
        if($hostname == false)
        {
            throw new Exception('The ' . $db_var . ' database configuration undefined in your config/database.php file.');
        }
        
        //----------- MONGO PACKAGE SUPPORT ------------//
        
        if(strtolower($driver) == 'mongo') 
        {
            $mongo = new Mongo_Db(false);
            return $mongo->connect();
        }

        switch (getComponent('db')) {
            case 'Database_Pdo':
                $database = new Database_Pdo();
                return $database->connect($driver, $options);
                break;
            case 'Mongo':    // Globally mongo db support.
                $mongo = new Mongo_Db(false);
                return $mongo->connect();
                break;
            case 'Database': // @todo Native database support. ( Somebody may want add native db packages. )
                $database = new Database();
                return $database->connect($driver, $options); 
                break;
        }
        
        return false;        
    }
}

/* End of file db.php */
/* Location: ./packages/db/releases/0.0.1/db.php */