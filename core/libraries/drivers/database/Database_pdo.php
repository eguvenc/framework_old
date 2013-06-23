<?php

/**
 * Obullo Framework (c) 2009 - 2012.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 *
 * @package         Obullo
 * @author          Obullo.com  
 * @subpackage      Obullo.database        
 * @copyright       Obullo Team
 * @license         public
 * @since           Version 1.0
 * @filesource
 */

function ob_query_timer($mark = '')
{
    list($sm, $ss) = explode(' ', microtime());

    return ($sm + $ss);
}

// ------------------------------------------------------------------------

require (BASE .'config'. DS .'pdo_constants'. EXT);

// ------------------------------------------------------------------------

require (BASE .'libraries'. DS .'drivers'. DS .'database'. DS .'Database_crud'. EXT);

/**
 * Obullo Database PDO.
 *
 * @package         Obullo 
 * @subpackage      Obullo.database     
 * @category        Database
 * @version         0.1
 * 
 */

Class OB_Database_pdo extends OB_crud {
    
    public $prepare                 = FALSE;    // prepare switch
    public $p_opt                   = array();  // prepare options
    public $last_sql                = NULL;     // stores last queried sql
    public $last_values             = array();  // stores last executed PDO values by exec_count

    public $query_count             = 0;        // count all queries.
    public $exec_count              = 0;        // count exec methods.
    // public $queries                 = array();  // stores queries for profiler
    // public $cached_queries          = array();  // stores cached queries for profiler
    // public $query_times             = array();  // query time for profiler
    public $prep_queries            = array();

    public $benchmark               = '';       // stores benchmark info
    
    public $current_row             = 0;        // stores the current row
    public $stmt_result             = array();  // stores current result for first_row() next_row() iteration

    public $use_bind_values         = FALSE;    // bind value usage switch
    public $use_bind_params         = FALSE;    // bind param usage switch
    public $last_bind_values        = array();  // Last bindValues and bindParams
    public $last_bind_params        = array();  // We store binds values to array()
                                                // because of we need it in last_query() function

    private $Stmt                   = NULL;     // PDOStatement Object
    /**
    * Pdo connection object.
    *
    * @var string
    */
    public $_conn = NULL;
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
    public function pdo_connect($dsn, $user = NULL, $pass = NULL, $options = NULL)
    {
        lib('ob/Lang')->load('ob/db');

        $this->_conn = new PDO($dsn, $user, $pass, $options);

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
        $this->prepare = TRUE;

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
    public function query($sql = NULL)
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
        $start_time = ob_query_timer('start');

        $this->Stmt = $this->_conn->query($sql);

        // $this->queries[] = $sql;   // Save the  query for debugging

        $end_time   = ob_query_timer('end');
        //------------------------------------

        // $this->benchmark +=    $end_time - $start_time;
        // $this->query_times[] = $end_time - $start_time;
        
        if(config('log_queries'))
        {
            log_me('debug', 'SQL: '.trim(preg_replace('/\n/', ' ', $sql), "\n").' time: '.number_format($end_time - $start_time, 4));   
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
    public function escape_like($str, $side = 'both')
    {
        return $this->escape_str($str, TRUE, $side);
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
            return $this->escape_str($str);
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
            return ($str === FALSE) ? 0 : 1;
        }
        
        if(is_null($str))
        {
            return 'NULL';
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
    * @param    array   $array bound, DEFAULT MUST BE NULL.
    * @param    string  $bind_value
    * @return   object  | void
    */
    public function exec($array = NULL)
    {
        if(is_array($array))
        {
            loader::helper('ob/array');
            
            if( ! is_assoc_array($array))
            {
                throw new Exception(lang('db_bind_data_must_assoc'));
            }
        }

        //------------------------------------
        
        $start_time = ob_query_timer('start');

        $this->Stmt->execute($array);

        // $this->cached_queries[] = end($this->prep_queries);   // Save the "cached" query for debugging

        $end_time   = ob_query_timer('end');
        
        //------------------------------------

        // $this->benchmark += $end_time - $start_time;
        // $this->query_times['cached'][] = $end_time - $start_time;
        
        if(config('log_queries'))
        {
            if(sizeof($this->prep_queries) > 0)
            {
                log_me('debug', 'SQL: '.trim(preg_replace('/\n/', ' ', end($this->prep_queries)), "\n").' ( Prepared Query ) time: '.number_format($end_time - $start_time, 4));
            }
        }

        // reset prepare variable and prevent collision with next query ..
        $this->prepare = FALSE;

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
        $this->use_bind_values  = FALSE;
        $this->use_bind_params  = FALSE;
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
    public function exec_query($sql)
    {
        $this->last_sql = $sql;

        //------------------------------------
        $start_time = ob_query_timer('start');

        // $this->queries[] = $sql;    // Save the  query for debugging

        $affected_rows = $this->_conn->exec($sql);

        $end_time   = ob_query_timer('end');
        //------------------------------------

        if(config('log_queries'))
        {
            if(sizeof($this->prep_queries) > 0)
            {
                log_me('debug', 'SQL: '.trim(preg_replace('/\n/', ' ', end($this->prep_queries)), "\n").' ( Exec Query ) time: '.number_format($end_time - $start_time, 4));
            }
        }
        
        // $this->benchmark +=    $end_time - $start_time;
        // $this->query_times[] = $end_time - $start_time;

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
    public function last_query($prepared = FALSE)
    {
        loader::helper('ob/array');
        
        // let's make sure, is it prepared query ?
        if($prepared == TRUE AND is_assoc_array($this->last_values))
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
    public function insert_id()
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
    public function bind_value($param, $val, $type)
    {
        $this->Stmt->bindValue($param, $val, $type);

        $this->use_bind_values = TRUE;
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
    public function bind_param($param, $val, $type, $length = NULL, $driver_options = NULL)
    {
        $this->Stmt->bindParam($param, $val, $type, $length, $driver_options);

        $this->use_bind_params = TRUE;
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
        return current($this->Stmt->fetchAll(PDO::FETCH_ASSOC));
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
        return $this->Stmt->fetch(PDO::FETCH_OBJ);
    }

    // --------------------------------------------------------------------

    /**
    * Get number of rows, Does not support all db drivers.
    *
    * @return  integer
    */
    public function row_count()
    {
        return $this->Stmt->rowCount();
    }
    
    // --------------------------------------------------------------------

    /**
    * Alias of row_count();
    *
    * @return  integer
    */
    public function num_rows()
    {
        return $this->row_count();
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Alias of row_count();
    *
    * @return  integer
    */
    public function count()
    {
        return $this->row_count();
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get results for current db 
    * operation. (first_row(), next_row() .. )
    *     
    * @access   private
    * @param    integer $type
    * @return   array
    */
    private function _stmt_result($type)
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
    public function first_row($type = obj)
    {
        $result = $this->_stmt_result($type);

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
    public function last_row($type = obj)
    {
        $result = $this->_stmt_result($type);

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
    public function next_row($type = obj)
    {
        $result = $this->_stmt_result($type);

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
    public function previous_row($type = obj)
    {
        $result = $this->_stmt_result($type);

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
    public function fetch_object($class = 'stdClass', array $config = array())
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
    public function get_attribute($key)
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
    public function get_colmeta($column)
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
        return current($this->Stmt->fetchAll(PDO::FETCH_BOTH));
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
           return $this->Stmt->fetch(PDO::FETCH_OBJ);
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
    public function fetch_all()
    {
        $arg = func_get_args();

        switch (sizeof($arg))
        {
           case 0:
           return $this->Stmt->fetchAll(PDO::FETCH_OBJ);
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
    public function fetch_column($col = NULL)
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
        return $this->Stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    // --------------------------------------------------------------------

    /**
    * CodeIgniter backward compatibility (result_array)
    *
    * @return  array
    */
    public function result_array()
    {
        return $this->Stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --------------------------------------------------------------------
    
    /**
    * CodeIgniter backward compatibility (row_array)
    *
    * @author CJ Lazell
    * @return  array
    */
    public function row_array()
    {
        return $this->Stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}

/* End of file Database_pdo.php */
/* Location: ./obullo/libraries/drivers/database/Database_pdo.php */