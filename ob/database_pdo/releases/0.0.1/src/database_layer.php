<?php
namespace Ob\Database_Pdo\Src;

function QueryTimer($mark = '')
{
    $mark = null;
    list($sm, $ss) = explode(' ', microtime());

    return ($sm + $ss);
}

/**
 * PDO Layer.
 *
 * @package         Obullo 
 * @subpackage      Obullo.database     
 * @category        Database
 * @version         0.1
 * 
 */

Class Database_Layer extends Database_Crud {
    
    public $prepare                 = false;    // prepare switch
    public $p_opt                   = array();  // prepare options
    public $last_sql                = null;     // stores last queried sql
    public $last_values             = array();  // stores last executed PDO values by exec_count

    public $query_count             = 0;        // count all queries.
    public $exec_count              = 0;        // count exec methods.
    // public $queries                 = array();  // stores queries for profiler
    // public $cached_queries          = array();  // stores cached queries for profiler
    // public $query_times             = array();  // query time for profiler
    public $prep_queries            = array();

    public $benchmark               = '';       // stores benchmark info
    
    public $current_row             = 0;        // stores the current row
    public $stmt_result             = array();  // stores current result for firstRow() nextRow() iteration

    public $use_bind_values         = false;    // bind value usage switch
    public $use_bind_params         = false;    // bind param usage switch
    public $last_bind_values        = array();  // Last bindValues and bindParams
    public $last_bind_params        = array();  // We store binds values to array()
                                                // because of we need it in lastQuery() function

    private $Stmt                   = null;     // PDOStatement Object
    /**
    * Pdo connection object.
    *
    * @var string
    */
    public $_conn = null;
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
        $this->_conn = new \PDO($dsn, $user, $pass, $options);

        return $this;
    }

    /**
    * Set PDO native Prepare() function
    *
    * @author   Ersin Guvenc
    * @param    array $options prepare options
    */
    public function prep($options = array())
    {
        $this->p_opt   = $options;
        $this->prepare = true;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * Flexible Prepared or Direct Query
    *
    * @author  Ersin Guvenc
    * @param   string $sql
    * @version 1.0
    * @version 1.1  added $this->exec_count
    * @return  object PDOStatement
    */
    public function query($sql = null)
    {
        $this->last_sql = $sql;

        if($this->prepare)
        {
            $this->Stmt = $this->_conn->prepare($sql, $this->p_opt);

            $this->prep_queries[] = $sql;  // Save the  query for debugging

            ++$this->query_count;

            return $this;
        }

        //------------------------------------
        
        $start_time = QueryTimer('start');

        $this->Stmt = $this->_conn->query($sql);

        $end_time   = QueryTimer('end');
        
        //------------------------------------
        
        if(\Ob\config('log_queries'))
        {
            \Ob\log\me('debug', 'SQL: '.trim(preg_replace('/\n/', ' ', $sql), "\n").' time: '.number_format($end_time - $start_time, 4));   
        }
        
        ++$this->query_count;

        return $this;
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
    * "Smart" Escape String via PDO
    *
    * Escapes data based on type
    * Sets boolean and null types
    *
    * @access    public
    * @param     string
    * @version   0.1
    * @version   0.2  Switched from using gettype() to is_ , some PHP versions might change its output.
    * @return    mixed
    */
    public function escape($str)
    {
        if(is_string($str))
        {
            return $this->escapeStr($str);
        }

        if(is_integer($str))
        {
            return (int)$str;
        }

        if(is_double($str))
        {
            return (double)$str;
        }

        if(is_float($str))
        {
            return (float)$str;
        }
        
        if(is_bool($str))
        {
            return ($str === false) ? 0 : 1;
        }
        
        if(is_null($str))
        {
            return 'null';
        }
        
    }

    // --------------------------------------------------------------------

    /**
    * Execute prepared query
    *
    * @author   Ersin Guvenc
    * @version  0.1
    * @version  0.2     added secure like conditions support
    * @version  0.3     changed bindValue functionality
    * @version  0.3     removed auto bind value, changed value storage
    * @param    array   $array bound, DEFAULT MUST BE null.
    * @param    string  $bind_value
    * @return   object  | void
    */
    public function exec($array = null)
    {
        if(is_array($array))
        {
            if( ! $this->isAssocArray($array))
            {
                throw new \Exception("PDO bind data must be associative array.");
            }
        }

        //------------------------------------
        
        $start_time = QueryTimer('start');
        
        $this->Stmt->execute($array);
        
        $end_time   = QueryTimer('end');
        
        //------------------------------------
        
        if(\Ob\config('log_queries'))
        {
            if(sizeof($this->prep_queries) > 0)
            {
                \Ob\log\me('debug', 'SQL: '.trim(preg_replace('/\n/', ' ', end($this->prep_queries)), "\n").' ( Prepared Query ) time: '.number_format($end_time - $start_time, 4));
            }
        }

        // reset prepare variable and prevent collision with next query ..
        $this->prepare = false;

        ++$this->exec_count;        // count execute of prepared statements ..

        $this->last_values = array();   // reset last bind values ..

        // store last executed bind values for last_query method.
        if(is_array($array))
        {
            $this->last_values[$this->exec_count] = $array;
        }
        elseif($this->use_bind_values)
        {
            $this->last_values[$this->exec_count] = $this->last_bind_values;
        }
        elseif($this->use_bind_params)
        {
            $this->last_values[$this->exec_count] = $this->last_bind_params;
        }

        // reset query bind usage informations ..
        $this->use_bind_values  = false;
        $this->use_bind_params  = false;
        $this->last_bind_values = array();
        $this->last_bind_params = array();

        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * Exec just for CREATE, DELETE, INSERT and
    * UPDATE operations it returns to
    * number of affected rows after the write
    * operations.
    *
    * @param    string $sql
    * @version  0.1
    * @return   boolean
    */
    public function execQuery($sql)
    {
        $this->last_sql = $sql;

        //------------------------------------
        $start_time = QueryTimer('start');

        $affected_rows = $this->_conn->exec($sql);

        $end_time   = QueryTimer('end');
        //------------------------------------

        if(\Ob\config('log_queries'))
        {
            if(sizeof($this->prep_queries) > 0)
            {
                \Ob\log\me('debug', 'SQL: '.trim(preg_replace('/\n/', ' ', end($this->prep_queries)), "\n").' ( Exec Query ) time: '.number_format($end_time - $start_time, 4));
            }
        }

        return $affected_rows;
    }

    // --------------------------------------------------------------------

    /**
    * Fetch prepared or none prepared last_query
    *
    * @author   Ersin Guvenc
    * @version  0.1
    * @version  0.2 added prepared param
    * @version  0.3 added bind_chr var and strpos function.
    * @param    boolean $prepared
    * @return   string
    */
    public function lastQuery($prepared = false)
    {
        // let's make sure, is it prepared query ?
        if($prepared == true AND $this->isAssocArray($this->last_values))
        {
            $bind_keys = array();
            foreach(array_keys($this->last_values[$this->exec_count]) as $k)
            {
                $bind_chr = ':';
                if(strpos($k, ':') === 0)   // If user use ':' characters
                $bind_chr = '';             // Some users forgot this character

                $bind_keys[]  = "/\\$bind_chr".$k.'\b/';  // escape bind ':' character
            }

            $quoted_vals = array();
            foreach(array_values($this->last_values[$this->exec_count]) as $v)
            {
                $quoted_vals[] = $this->quote($v);
            }

            $this->last_values = array();  // reset last values.

            return preg_replace($bind_keys, $quoted_vals, $this->last_sql);
        }

        return $this->last_sql;
    }

    // --------------------------------------------------------------------

    /**
    * PDO Last Insert Id
    *
    * @return  object PDO::Statement
    */
    public function insertId()
    {
        return $this->_conn->lastInsertId();
    }

    // --------------------------------------------------------------------

    /**
    * Alias of PDO_Statement::bindValue()
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

    // ------------------------------------------------------------------

    /**
    * Alias of PDO_Statement::bindParam()
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
    * Get available drivers on your host
    *
    * @return  object PDO::Statement
    */
    public function drivers()
    {
        return $this->_conn->getAvailableDrivers();
    }

    // --------------------------------------------------------------------

    /**
    * Get results as associative array
    *
    * @return  array
    */
    public function assoc()
    {
        return current($this->Stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    // --------------------------------------------------------------------

    /**
    * Get results as object
    *
    * @return  object
    */
    public function obj()
    {
        return $this->row();
    }

    // --------------------------------------------------------------------

    /**
    * Alias of $this-db->obj()
    *
    * @return  object
    */
    public function row()
    {
        return $this->Stmt->fetch(\PDO::FETCH_OBJ);
    }

    // --------------------------------------------------------------------

    /**
    * Get number of rows, Does not support all db drivers.
    *
    * @return  integer
    */
    public function rowCount()
    {
        return $this->Stmt->rowCount();
    }
    
    // --------------------------------------------------------------------

    /**
    * Alias of rowCount();
    *
    * @return  integer
    */
    public function numRows()
    {
        return $this->rowCount();
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Alias of rowCount();
    *
    * @return  integer
    */
    public function count()
    {
        return $this->rowCount();
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get results for current db 
    * operation. (firstRow(), nextRow() .. )
    *     
    * @access   private
    * @param    integer $type
    * @return   array
    */
    private function _stmtResult($type)
    {
        if(count($this->stmt_result) > 0)
        {
            return $this->stmt_result;
        }
        
        $this->stmt_result = $this->Stmt->fetchAll($type);
        
        return $this->stmt_result;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Returns the "first" row
    *
    * @access    public
    * @return    object
    */    
    public function firstRow($type = obj)
    {
        $result = $this->_stmtResult($type);

        if (count($result) == 0)
        {
            return $result;
        }
        
        return $result[0];
    }

    // --------------------------------------------------------------------
    
    /**
    * Returns the "last" row
    *
    * @access    public
    * @return    object
    */    
    public function lastRow($type = obj)
    {
        $result = $this->_stmtResult($type);

        if (count($result) == 0)
        {
            return $result;
        }
        return $result[count($result) -1];
    }    
    
    // --------------------------------------------------------------------

    /**
    * Returns the "next" row
    *
    * Returns the next row results. next_rowset doesn't work for the mysql
    * driver. Also adds backwards compatibility for Codeigniter.
    *
    * @author CJ Lazell
    * @access	public
    * @return	object
    */	
    public function nextRow($type = obj)
    {
        $result = $this->_stmtResult($type);

        if(count($result) == 0)
        {
            return $result;
        }
        
        if(isset($result[$this->current_row + 1]))
        {
            ++$this->current_row;
        }

        return $result[$this->current_row];
    }

    // --------------------------------------------------------------------

    /**
    * Returns the "previous" row
    *
    * @access    public
    * @return    object
    */    
    public function previousRow($type = obj)
    {
        $result = $this->_stmtResult($type);

        if (count($result) == 0)
        {
            return $result;
        }

        if (isset($result[$this->current_row - 1]))
        {
            --$this->current_row;
        }
        return $result[$this->current_row];
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Fetches the next row and returns it as an object.
    *
    * @param    string $class  OPTIONAL Name of the class to create.
    * @param    array  $config OPTIONAL Constructor arguments for the class.
    * @return   mixed One object instance of the specified class.
    */
    public function fetchObject($class = 'stdClass', array $config = array())
    {
        return $this->Stmt->fetchObject($class, $config);
    }

    // --------------------------------------------------------------------

    /**
    * Retrieve a statement attribute.
    *
    * @param   integer $key Attribute name.
    * @return  mixed      Attribute value.
    */
    public function getAttribute($key)
    {
        return $this->Stmt->getAttribute($key);
    }

    // --------------------------------------------------------------------

    /**
    * Returns metadata for a column in a result set.
    *
    * @param int $column
    * @return mixed
    */
    public function getColmeta($column)
    {
        return $this->Stmt->getColumnMeta($column);
    }

    /**
    * Get column names and numbers (both)
    *
    * @return  mixed
    */
    public function both()
    {
        return current($this->Stmt->fetchAll(\PDO::FETCH_BOTH));
    }

    // --------------------------------------------------------------------

    /**
    * Native PDOStatement::fetch() function
    *
    * @param    int $fetch_style = PDO::FETCH_BOTH
    * @param    int $cursor_orientation = PDO::FETCH_ORI_NEXT
    * @param    $cursor_offset = 0
    * @return   object
    */
    public function fetch()
    {
        $arg = func_get_args();

        switch (sizeof($arg))
        {
           case 0:
           return $this->Stmt->fetch(\PDO::FETCH_OBJ);
             break;
           case 1:
           return $this->Stmt->fetch($arg[0]);
             break;
           case 2:
           return $this->Stmt->fetch($arg[0], $arg[1]);
             break;
           case 3:
           return $this->Stmt->fetch($arg[0], $arg[1], $arg[2]);
             break;
        }
    }

    // --------------------------------------------------------------------

    /**
    * Get "all results" by assoc, object, num, bound or
    * anything what u want
    *
    * @param    int $fetch_style  = PDO::FETCH_BOTH
    * @param    int $column_index = 0
    * @param    array $ctor_args  = array()
    * @return   object
    */
    public function fetchAll()
    {
        $arg = func_get_args();

        switch (sizeof($arg))
        {
           case 0:
           return $this->Stmt->fetchAll(\PDO::FETCH_OBJ);
             break;
           case 1:
           return $this->Stmt->fetchAll($arg[0]);
             break;
           case 2:
           return $this->Stmt->fetchAll($arg[0], $arg[1]);
             break;
           case 3:
           return $this->Stmt->fetchAll($arg[0], $arg[1], $arg[2]);
             break;
        }
    }

    // --------------------------------------------------------------------

    /**
    * Returns a single column from the next row of a result set
    *
    * @param object
    */
    public function fetchColumn($col = null)
    {
        return $this->Stmt->fetchColumn($col);
    }

    // --------------------------------------------------------------------

    /**
    * CodeIgniter backward compatibility (result)
    *
    * @return object
    */
    public function result()
    {
        return $this->Stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    
    // --------------------------------------------------------------------

    /**
    * CodeIgniter backward compatibility (result_array)
    *
    * @return  array
    */
    public function resultArray()
    {
        return $this->Stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // --------------------------------------------------------------------
    
    /**
    * CodeIgniter backward compatibility (row_array)
    *
    * @author CJ Lazell
    * @return  array
    */
    public function rowArray()
    {
        return $this->Stmt->fetch(\PDO::FETCH_ASSOC);
    }
 
    // --------------------------------------------------------------------
    
    /**
    * Check Array is_associative
    * or not.
    * 
    * @param type $a
    * @return type 
    */
    private function isAssocArray( $a )
    {
        return is_array( $a ) && ( count( $a ) !== array_reduce( array_keys( $a ), create_function( '$a, $b', 'return ($b === $a ? $a + 1 : 0);' ), 0 ) );
    }
    
}

/**
| PDO paramater type constants
| @link http://php.net/manual/en/pdo.constants.php
| These prefs are used when working with query results.
*/
define('PARAM_NULL', 0);  // null
define('PARAM_INT' , 1);  // integer
define('PARAM_STR' , 2);  // string
define('PARAM_LOB' , 3);  // integer  Large Object Data (lob)
define('PARAM_STMT', 4);  // integer  Represents a recordset type ( Not currently supported by any drivers).
define('PARAM_BOOL', 5);  // boolean                                
define('PARAM_INOUT' , -2147483648); // PDO::PARAM_INPUT_OUTPUT integer
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

/* End of file database_layer.php */
/* Location: ./ob/database_pdo/releases/0.0.1/src/database_layer.php */