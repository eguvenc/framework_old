<?php
namespace Database_Pdo\Src;

/**
 * Database Adapter Class
 *
 * @package       packages
 * @subpackage    database_pdo/src
 * @category      database adapter
 * @link
 */
Abstract Class Database_Adapter extends Database_Layer {
    
    public $hostname = '';
    public $username = '';
    public $password = '';
    public $database = '';
    public $driver   = '';
    public $char_set = '';
    public $dbh_port = '';
    public $dsn      = '';
    public $options  = array();
    
    public $prefix   = '';
    public $swap_pre = '';
    
    /**
    * Db object.
    * 
    * @var string
    */
    public $_pdo = null;  
    
    /**
    * The character used for escaping
    * 
    * @var string
    */
    public $_escape_char = '`';
    
    
    // Set Database Variables
    public function __construct($param)
    {
        $db_var = $param['default_db'];
        
        if(isset($param['driver']))
        {    
            if( isset($param['dsn']) )  // Dsn Connection..
            {
                $this->driver   = strtolower($param['driver']);  // required
                $this->username = isset($param['username']) ? $param['username'] : '';    // optional
                $this->password = isset($param['password']) ? $param['password'] : '';    // optional
                $this->char_set = isset($param['char_set']) ? $param['char_set'] : '';    // optional
                $this->dsn      = $param['dsn'];                   // required
                $this->options  = isset($param['options']) ? $param['options'] : array(); // optional
                
            } else 
            {
                // Standart Connection..
                $this->hostname = $param['hostname'];             // required
                $this->username = $param['username'];             // required
                $this->password = $param['password'];             // required
                $this->database = $param['database'];             // required
                $this->driver   = strtolower($param['driver']); // required
                $this->prefix   = strtolower($param['prefix']); // optional
                $this->swap_pre = strtolower($param['swap_pre']); // optional
                $this->char_set = isset($param['char_set']) ? $param['char_set'] : '';    // optional
                $this->dbh_port = isset($param['dbh_port']) ? $param['dbh_port'] : '';    // optional
                $this->options  = isset($param['options']) ? $param['options'] : array(); // optional
            }
               
        } else 
        {
            // Config.database connection
            $this->hostname = db('hostname',$db_var); 
            $this->username = db('username',$db_var); 
            $this->password = db('password',$db_var); 
            $this->database = db('database',$db_var);
            $this->driver   = strtolower(db('driver',$db_var));
            $this->prefix   = strtolower(db('prefix',$db_var));
            $this->swap_pre = strtolower(db('swap_pre',$db_var));
            $this->char_set = db('char_set',$db_var);
            $this->dbh_port = db('dbh_port',$db_var);
            $this->dsn      = db('dsn'     ,$db_var);
            $this->options  = db('options' ,$db_var);
        }  
        
        if( ! is_array($this->options) ) $this->options = array();
        
    }
    
    // --------------------------------------------------------------------                      
    
    /**
    * Connect to PDO (default Mysql)
    * 
    * @param    string $dsn  Dsn
    * @param    string $user Db username
    * @param    mixed  $pass Db password
    * @param    array  $options Db Driver options
    * @return   void
    */
    public function _connect()
    {
        // If connection is ok .. not need to again connect..
        if ($this->_conn) { return; }
        
        $port = empty($this->dbh_port) ? '' : 'port:'.$this->dbh_port.';';
        $dsn  = empty($this->dsn) ? 'mysql:host='.$this->hostname.';'.$port.'dbname='.$this->database : $this->dsn;
        
        // array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $this->char_set") it occurs an error !
        $this->_pdo = $this->pdoConnect($dsn, $this->username, $this->password, $this->options);
             
        if( ! empty($this->char_set) )
        {
            $this->_conn->exec("SET NAMES '" . $this->char_set . "'");
        }
        
        // We set exception attribute for always showing the pdo exceptions errors. (ersin)
        $this->_conn->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
    }     
    
    // --------------------------------------------------------------------

    /**
    * Get PDO Instance
    * for DBFactory Class ..
    */
    public function getConnection()
    {
       return $this->_pdo;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Test if a connection is active
     *
     * @return boolean
     */
    public function isConnected()
    {
        return ((bool) ($this->_conn instanceof \PDO));
    }

    // --------------------------------------------------------------------
    
    /**
    * Reconnect
    *
    * Keep / reestablish the db connection if no queries have been
    * sent for a length of time exceeding the server's idle timeout
    *
    * @access    public
    * @return    void
    */
    public function reconnect()
    {
        $this->_connect();
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get Database Version number.
    *
    * @access    public
    * @return    string
    */
    public function version()
    {
        $this->_connect();
        
        try
        {
            $version = $this->_conn->getAttribute(\PDO::ATTR_SERVER_VERSION);
            
        } catch (\PDOException $e)  // don't show excepiton
        {
            return null; // If the driver doesn't support getting attributes
        }
        
        $matches = null;
        if (preg_match('/((?:[0-9]{1,2}\.){1,3}[0-9]{1,2})/', $version, $matches))
        {
            return $matches[1];
        } 
        else 
        {
            return null;
        }
    }

    // --------------------------------------------------------------------
    
    /**
    * Begin a transaction.
    */
    public function transaction()
    {
        $this->__wakeup();
        $this->_conn->beginTransaction();
        
        return $this;
    }

    // --------------------------------------------------------------------
    
    /**
    * Commit a transaction.
    */
    public function commit()
    {
        $this->__wakeup(); 
        $this->_conn->commit();
        
        return $this;
    }
    
    // --------------------------------------------------------------------

    /**
    * Roll-back a transaction.
    */
    public function rollback()
    {    
        $this->__wakeup();
        $this->_conn->rollBack();
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    public function __sleep()
    {   
        return array(
        'hostname', 
        'username', 
        'password', 
        'database', 
        'driver', 
        'char_set', 
        'dbh_port', 
        'dsn', 
        'options');
    }
    
    // --------------------------------------------------------------------
    
    public function __wakeup()
    {
        $this->_connect();
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Set pdo attribute
    * 
    * @param type $key
    * @param type $val 
    */
    public function setAttribute($key, $val)
    {
        $this->_conn->setAttribute($key, $val);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Return error info in PDO::PDO::ERRMODE_SILENT mode
    * 
    * @return type 
    */
    public function errors()
    {
        return $this->_conn->errorInfo();
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Close the database connetion. 
     */
    function __destruct()
    {
        $this->_conn = null;
    }
    
}

/* End of file database_adapter.php */
/* Location: ./packages/database_pdo/releases/0.0.1/src/database_adapter.php */