<?php

/**
 * Pdo Database Adapter Class
 *
 * @package       packages
 * @subpackage    database_pdo/src
 * @category      database adapter
 * @link
 */
Class Pdo_Adapter
{
    /**
     * result into $sql var
     * 
     * @var string
     */
    public $sql;
    
    // Db Config
    //--------------------------------------------------------------

    public $hostname = '';
    public $username = '';
    public $password = '';
    public $database = '';
    public $driver = ''; // optional
    public $char_set = ''; // optional
    public $dbh_port = ''; // optional
    public $dsn = ''; // optional
    public $options = array(); // optional
    public $prefix = '';

    //--------------------------------------------------------------
    
    public $prepare = false;    // Prepare used or not
    public $last_sql = null;     // stores last queried sql
    public $last_values = array();  // stores last executed PDO values by exec_count
    public $query_count = 0;        // count all queries.
    public $exec_count = 0;        // count exec methods.
    public $prep_queries = array();
    public $use_bind_values = false;    // bind value usage switch
    public $use_bind_params = false;    // bind param usage switch
    public $last_bind_values = array();  // Last bindValues and bindParams
    public $last_bind_params = array();  // We store binds values to array()

    // Private variables
    //--------------------------------------------------------------
    public $_protect_identifiers  = true;
    public $reservedIdentifiers = array('*'); // Identifiers that should NOT be escaped

    public $Stmt = null;     // PDOStatement Object
    
    //--------------------------------------------------------------

    public $pdo_crud = null;  // Pdo Crud Object;

    /**
     * Pdo connection object.
     *
     * @var string
     */
    public $_conn = null;

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

    /**
     * Build database variables
     * 
     * @param array $param
     */
    public function __construct($param)
    {
        if (isset($param['dsn']) AND !empty($param['dsn'])) {  // Dsn Connection..
            $this->driver = $param['driver'];  // optional
            $this->username = isset($param['username']) ? $param['username'] : '';    // required
            $this->password = isset($param['password']) ? $param['password'] : '';    // required
            $this->char_set = isset($param['char_set']) ? $param['char_set'] : '';    // optional
            $this->dsn = $param['dsn'];                   // required
            $this->options = isset($param['options']) ? $param['options'] : array(); // optional
        } else {  // Standart Connection..
            $this->hostname = $param['hostname'];           // required
            $this->username = $param['username'];           // required
            $this->password = $param['password'];           // required
            $this->database = $param['database'];           // required
            $this->driver   = $param['driver'];  // optional
            $this->prefix   = strtolower($param['prefix']); // optional
            $this->char_set = isset($param['char_set']) ? $param['char_set'] : '';    // optional
            $this->dbh_port = isset($param['dbh_port']) ? $param['dbh_port'] : '';    // optional
            $this->options  = isset($param['options']) ? $param['options'] : array(); // optional
        }
        if ( ! is_array($this->options)) {
            $this->options = array();
        }
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
    public function connect()
    {
        if ($this->connection) {   // If connected .. not need to again connect..
            return;
        }
        $port = empty($this->dbh_port) ? '' : 'port:' . $this->dbh_port . ';';
        $dsn  = empty($this->dsn) ? 'mysql:host=' . $this->hostname . ';' . $port . 'dbname=' . $this->database : $this->dsn;

        // array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $this->char_set") it occurs an error !
        $this->pdo = $this->pdoConnect($dsn, $this->username, $this->password, $this->options);

        if ( ! empty($this->char_set)) {
            $this->connection->exec("SET NAMES '" . $this->char_set . "'");
        }
        // We set exception attribute for always showing the pdo exceptions errors. (ersin)
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // --------------------------------------------------------------------

    /**
     * Connect to PDO
     *
     * @author   Ersin Guvenc
     * @param    string $dsn  Dsn
     * @param    string $user Db username
     * @param    mixed  $pass Db password
     * @param    array  $options Db Driver options
     * @return   void
     */
    public function pdoConnect($dsn, $user = null, $pass = null, $options = null)
    {
        $this->connection = new PDO($dsn, $user, $pass, $options);
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Set PDO native Prepare() function
     *
     * @param string $sql     prepared query
     * @param array  $options prepare options
     *
     * @return object adapter
     */
    public function prepare($sql, $options = array())
    {
        $this->Stmt = $this->connection->prepare($sql, $options);
        $this->prep_queries[] = $sql;  // Save the  query for debugging
        $this->prepare = true;
        ++$this->query_count;
        return ($this);
    }

    // --------------------------------------------------------------------

    /**
     * Flexible Prepared or Direct Query
     *
     * @param   string $sql
     * @return  object PDOStatement
     */
    public function query($sql = null)
    {
        global $config, $logger;

        $this->last_sql = $sql;

        //------------------------------------
        
        $start = microtime(true);

        $this->Stmt = $this->connection->query($sql);
        
        $time  = microtime(true) - $start;

        //------------------------------------

        if ($config['log_queries']) {
            $logger->debug('$_SQL ( Query ):', array('time' => number_format($time, 4), 'output' => trim(preg_replace('/\n/', ' ', $sql), "\n")));
        }
        ++$this->query_count;
        return ($this);
    }

    // --------------------------------------------------------------------

    /**
     * PDO Last Insert Id
     *
     * @return  object PDO::Statement
     */
    public function insertId()
    {
        return $this->connection->lastInsertId();
    }

    // --------------------------------------------------------------------

    /**
     * Check simply array is associative ?
     * 
     * @param type $a
     * @return type 
     */
    public function isAssocArray($arr)
    {
        return is_array($arr) AND array_keys($arr) !== range(0, count($arr) - 1);
    }

    // --------------------------------------------------------------------

    /**
     * Get PDO Instance
     * for DBFactory Class ..
     */
    public function getConnection()
    {
        return $this->pdo;
    }

    // --------------------------------------------------------------------

    /**
     * Test if a connection is active
     *
     * @return boolean
     */
    public function isConnected()
    {
        return ((bool) ($this->connection instanceof PDO));
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
        $this->connect();
    }

    // --------------------------------------------------------------------

    /**
     * Get Database Version number.
     *
     * @access    public
     * @return    string
     */
    public function getVersion()
    {
        $this->connect();
        try {
            $version = $this->connection->getAttribute(PDO::ATTR_SERVER_VERSION);
        } catch (PDOException $e) {  // don't show excepiton
            return null; // If the driver doesn't support getting attributes
        }

        $matches = null;
        if (preg_match('/((?:[0-9]{1,2}\.){1,3}[0-9]{1,2})/', $version, $matches)) {
            return $matches[1];
        } else {
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
        $this->connection->beginTransaction();
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Commit a transaction.
     */
    public function commit()
    {
        $this->__wakeup();
        $this->connection->commit();
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Check active transaction status
     * 
     * @return bool
     */
    public function inTransaction()
    {
        return $this->connection->inTransaction();
    }

    // --------------------------------------------------------------------

    /**
     * Roll-back a transaction.
     */
    public function rollBack()
    {
        $this->__wakeup();
        $this->connection->rollBack();
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
        $this->connect();
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
        $this->connection->setAttribute($key, $val);
    }

    // --------------------------------------------------------------------

    /**
     * Get pdo attribute
     * 
     * @param type $key
     * @param type $val 
     */
    public function getAttribute($key)
    {
        return $this->connection->getAttribute($key);
    }

    // --------------------------------------------------------------------

    /**
     * Return error info in PDO::PDO::ERRMODE_SILENT mode
     * 
     * @return type 
     */
    public function getErrors()
    {
        return $this->connection->errorInfo();
    }

    // --------------------------------------------------------------------

    /**
     * Get available drivers on your host
     *
     * @return  object PDO::Statement
     */
    public function getDrivers()
    {
        return $this->connection->getAvailableDrivers();
    }

    // --------------------------------------------------------------------

    /**
     * Equal to PDO_Statement::bindParam()
     *
     * @param   mixed $param
     * @param   mixed $val
     * @param   mixed $type  PDO FETCH CONSTANT
     * @param   mixed $length
     * @param   mixed $driver_options
     */
    public function bindParam($param, $val, $type, $length = null, $driver_options = null)
    {
        $this->Stmt->bindParam($param, $val, $type, $length, $driver_options);
        $this->use_bind_params = true;
        $this->last_bind_params[$param] = $val;
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Equal to PDO_Statement::bindValue()
     *
     * @param   string $param
     * @param   mixed $val
     * @param   string $type PDO FETCH CONSTANT
     */
    public function bindValue($param, $val, $type)
    {
        $this->Stmt->bindValue($param, $val, $type);
        $this->use_bind_values = true;
        $this->last_bind_values[$param] = $val;
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * "Smart" Escape String via PDO
     *
     * Escapes data based on type
     * Sets boolean and null types
     *
     * @access    public
     * @param     string
     * @return    mixed
     */
    public function escape($str)
    {
        if (is_string($str)) {
            return $this->escapeStr($str);
        }
        if (is_integer($str)) {
            return (int) $str;
        }
        if (is_double($str)) {
            return (double) $str;
        }
        if (is_float($str)) {
            return (float) $str;
        }
        if (is_bool($str)) {
            return ($str === false) ? 0 : 1;
        }
        if (is_null($str)) {
            return 'null';
        }
    }

    // --------------------------------------------------------------------

    /**
     * Escape LIKE String
     *
     * Calls the individual driver for platform
     * specific escaping for LIKE conditions
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public function escapeLike($str, $side = 'both')
    {
        return $this->escapeStr($str, true, $side);
    }

    // --------------------------------------------------------------------

    /**
     * Execute prepared query
     *
     * @param    array   $array bound, DEFAULT MUST BE null.
     * @param    string  $bind_value
     * @return   object  | void
     */
    public function execute($array = null)
    {
        global $config, $logger;

        if ( ! empty($array) AND ! $this->isAssocArray($array)) {
            throw new Exception('PDO bind data must be associative array');
        }

        //------------------------------------

        $start = microtime(true);

        $this->Stmt->execute($array);

        $time  = microtime(true) - $start;

        //------------------------------------

        if ($config['log_queries'] AND isset($this->prep_queries[0])) {
            $logger->debug('$_SQL ( Execute ):', array('time' => number_format($time, 4), 'output' => trim(preg_replace('/\n/', ' ', end($this->prep_queries)), "\n")));
        }

        $this->prepare = false;   // reset prepare variable and prevent collision with next query ..
        ++$this->exec_count;      // count execute of prepared statements ..

        $this->last_values = array();   // reset last bind values ..

        if (is_array($array)) {         // store last executed bind values for last_query method.
            $this->last_values[$this->exec_count] = $array;
        } elseif ($this->use_bind_values) {
            $this->last_values[$this->exec_count] = $this->last_bind_values;
        } elseif ($this->use_bind_params) {
            $this->last_values[$this->exec_count] = $this->last_bind_params;
        }

        $this->use_bind_values = false;         // reset query bind usage data ..
        $this->use_bind_params = false;
        $this->last_bind_values = array();
        $this->last_bind_params = array();

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * execQuery just for CREATE, DELETE, INSERT and
     * UPDATE operations it returns to
     * number of [affected rows] after the write
     * operations.
     *
     * @param    string $sql
     * @return   boolean
     */
    public function exec($sql)
    {
        global $config, $logger;
        $this->last_sql = $sql;

        //------------------------------------

        $start = microtime(true);

        $affected_rows = $this->connection->exec($sql);

        $time  = microtime(true) - $start;

        //------------------------------------

        if ($config['log_queries']) {
            $logger->debug('$_SQL ( Exec ):', array('time' => number_format($time, 4), 'output' => trim(preg_replace('/\n/', ' ', $sql), "\n")));
        }
        return $affected_rows;
    }

    // --------------------------------------------------------------------

    /**
     * Equal to PDO::rowCount();
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->Stmt->rowCount();
    }

    // --------------------------------------------------------------------

    /**
     * Fetch all results as array & if fail return BOOLEAN
     *
     * @return booelan | array
     */
    public function getResultArray()
    {
        return $this->Stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --------------------------------------------------------------------

    /**
     * Fetch one result as object & if fail return BOOLEAN
     *
     * @return boolean | object
     */
    public function getRow()
    {
        return $this->Stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Fetch all result as object & if fail return BOOLEAN
     *
     * @return boolean | object
     */
    public function getResult()
    {
        return $this->Stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // --------------------------------------------------------------------

    /**
     * Result row as array & if fail return BOOLEAN
     * 
     * @return  boolean | array
     */
    public function getRowArray()
    {
        return $this->Stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --------------------------------------------------------------------

    /**
     * Get results as array & if fail return ARRAY
     * 
     * @return array
     */
    public function resultsArray()
    {
        $result = $this->getResultArray();
        if ($result === false) {
            return array();
        }
        return $result;
    }

    // --------------------------------------------------------------------
    
    /**
     * Get row as array & if fail return ARRAY
     * 
     * @return array
     */
    public function rowArray()
    {
        $result = $this->getRowArray();
        if ($result === false) {
            return array();
        }
        return $result;
    }

    // --------------------------------------------------------------------

    /**
     * Get the statement object
     * and use native pdo functions
     *
     *  $stmt = $this->db->getStatement();
     *  $stmt->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
     *  
     *  @see  http://publib.boulder.ibm.com/infocenter/db2luw/v9/index.jsp?topic=%2Fcom.ibm.db2.udb.apdv.php.doc%2Fdoc%2Fr0022486.htm
     * 
     * @return [type] [description]
     */
    public function getStatement()
    {
        return $this->Stmt;
    }

    // --------------------------------------------------------------------

    /**
     * Alias of getLastQuery
     * 
     * @return string
     */
    public function lastQuery()
    {
        return $this->last_sql;
    }

    // --------------------------------------------------------------------

    /**
     * Close the database connetion. 
     */
    public function __destruct()
    {
        $this->connection = null;
    }

}

/*
  | PDO paramater type constants
  | @link http://php.net/manual/en/pdo.constants.php
  | These prefs are used when working with query results.
 */
define('PARAM_NULL', 0);  // null
define('PARAM_INT', 1);  // integer
define('PARAM_STR', 2);  // string
define('PARAM_LOB', 3);  // integer  Large Object Data (lob)
define('PARAM_STMT', 4);  // integer  Represents a recordset type ( Not currently supported by any drivers).
define('PARAM_BOOL', 5);  // boolean                                
define('PARAM_INOUT', -2147483648); // PDO::PARAM_INPUT_OUTPUT integer
define('LAZY', 1);
define('ASSOC', 2);
define('NUM', 3);
define('BOTH', 4);
define('OBJ', 5);
define('ROW', 5);
define('BOUND', 6);
define('COLUMN', 7);
define('AS_CLASS', 8);
define('FUNC', 10);
define('NAMED', 11);
define('KEY_PAIR', 12);
define('GROUP', 65536);
define('UNIQUE', 196608);
define('CLASS_TYPE', 262144);
define('SERIALIZE', 524288);
define('PROPS_LATE', 1048576);
define('ORI_NEXT', 0);
define('ORI_PRIOR', 1);
define('ORI_FIRST', 2);
define('ORI_LAST', 3);
define('ORI_ABS', 4);
define('ORI_REL', 5);
define('INTO', 9);


/* End of file pdo_adapter.php */
/* Location: ./packages/pdo_adapter/releases/0.0.1/pdo_adapter.php */