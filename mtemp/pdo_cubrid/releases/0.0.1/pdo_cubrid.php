<?php

/**
 * Cubrid Database Adapter Class
 *
 * @package       packages
 * @subpackage    pdo_cubrid
 * @category      database
 * @link
 */

Class Pdo_Cubrid extends Database_Pdo\Src\Database_Adapter
{
    /**
    * The character used for escaping
    *
    * @var string
    */
    public $_escape_char = '';


    // clause and character used for LIKE escape sequences - not used in MySQL
    public $_like_escape_str = '';
    public $_like_escape_chr = '';

    public function __construct($param)
    {
        parent::__construct($param);
    }

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
    public function _connect()
    {
        // If connection is ok .. not need to again connect..
        if ($this->_conn) { return; }

        // cubrid:host=localhost;port=33000;dbname=demodb
        
        $port = empty($this->dbh_port) ? ';port=33000' : ';port='.$this->dbh_port;
        $dsn  = empty($this->dsn) ? 'cubrid:host='.$this->hostname.$port.';dbname='.$this->database : $this->dsn;
       
        $this->_pdo = $this->pdoConnect($dsn, $this->username, $this->password, $this->options);

        // In CUBRID, there is no need to set charset or collation.
	// This is why returning true will allow the application continue
	// its normal process.

        // We set exception attribute for always showing the pdo exceptions errors. (ersin)
        $this->_conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // PDO::ERRMODE_SILENT
    }

    // --------------------------------------------------------------------

    /**
     * Escape the SQL Identifiers
     *
     * This function escapes column and table names
     *
     * @access   private
     * @param    string
     * @return   string
     */
    public function _escapeIdentifiers($item)
    {
        if ($this->_escape_char == '')
        {
            return $item;
        }

        foreach ($this->_reserved_identifiers as $id)
        {
            if (strpos($item, '.'.$id) !== false)
            {
                $str = $this->_escape_char. str_replace('.', $this->_escape_char.'.', $item);

                // remove duplicates if the user already included the escape
                return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
            }
        }

        if (strpos($item, '.') !== false)
        {
            $str = $this->_escape_char.str_replace('.', $this->_escape_char.'.'.$this->_escape_char, $item).$this->_escape_char;
        }
        else
        {
            $str = $this->_escape_char.$item.$this->_escape_char;
        }

        // remove duplicates if the user already included the escape
        return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
    }

    // --------------------------------------------------------------------

    /**
    * Escape String
    *
    * @author   Ersin Guvenc
    * @access   public
    * @param    string
    * @param    bool    whether or not the string will be used in a LIKE condition
    * @return   string
    */
    public function escapeStr($str, $like = false, $side = 'both')
    {
        if (is_array($str))
        {
            foreach($str as $key => $val)
            {
                $str[$key] = $this->escapeStr($val, $like);
            }

            return $str;
        }

        // escape LIKE condition wildcards
        if ($like === true)
        {
            $str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);

            switch ($side)
            {
               case 'before':
                 $str = "%{$str}";
                 break;

               case 'after':
                 $str = "{$str}%";
                 break;

               default:
                 $str = "%{$str}%";
            }

            // not need to quote for who use prepare and :like bind.
            if($this->prepare == true AND $this->is_like_bind)
            {
                return $str; 
            }
        }

        // make sure is it bind value, if not ...
        if($this->prepare === true)
        {
            if(strpos($str, ':') === false)
            {
                $str = $this->quote($str, PDO::PARAM_STR);
            }
        }
        else
        {
           $str = $this->quote($str, PDO::PARAM_STR);
        }

        return $str;
    }

    // --------------------------------------------------------------------

    /**
    * Platform specific pdo quote
    * function.
    *
    * @author  Ersin Guvenc.
    * @param   string $str
    * @param   int    $type
    * @return
    */
    public function quote($str, $type = null)
    {
         return $this->_conn->quote($str, $type);
    }

    // --------------------------------------------------------------------

    /**
    * From Tables
    *
    * This function implicitly groups FROM tables so there is no confusion
    * about operator precedence in harmony with SQL standards
    *
    * @access   public
    * @param    type
    * @return   type
    */
    public function _fromTables($tables)
    {
        if ( ! is_array($tables))
        {
            $tables = array($tables);
        }

        return '('.implode(', ', $tables).')';
    }

    // --------------------------------------------------------------------

    /**
     * Insert statement
     *
     * Generates a platform-specific insert string from the supplied data
     *
     * @access	public
     * @param	string	the table name
     * @param	array	the insert keys
     * @param	array	the insert values
     * @return	string
     */
    function _insert($table, $keys, $values)
    {
        return "INSERT INTO ".$table." (\"".implode('", "', $keys)."\") VALUES (".implode(', ', $values).")";
    }
    
    // --------------------------------------------------------------------

    /**
     * Replace statement
     *
     * Generates a platform-specific replace string from the supplied data
     *
     * @access	public
     * @param	string	the table name
     * @param	array	the insert keys
     * @param	array	the insert values
     * @return	string
     */
    function _replace($table, $keys, $values)
    {
        return "REPLACE INTO ".$table." (\"".implode('", "', $keys)."\") VALUES (".implode(', ', $values).")";
    }
    
    // --------------------------------------------------------------------

    /**
     * Update statement
     *
     * Generates a platform-specific update string from the supplied data
     *
     * @access   public
     * @param    string   the table name
     * @param    array    the update data
     * @param    array    the where clause
     * @param    array    the orderby clause
     * @param    array    the limit clause
     * @return   string
     */
    public function _update($table, $values, $where, $orderby = array(), $limit = false)
    {
        foreach($values as $key => $val)
        {
            // $valstr[] = $key." = ".$val;
            $valstr[] = sprintf('"%s" = %s', $key, $val);
            // output Array ( [0] => "0" = value ) 
        }

        $limit = ( ! $limit) ? '' : ' LIMIT '.$limit;

        $orderby = (count($orderby) >= 1)?' ORDER BY '.implode(", ", $orderby):'';

        $sql = "UPDATE ".$table." SET ".implode(', ', $valstr);

        $sql .= ($where != '' AND count($where) >=1) ? " WHERE ".implode(" ", $where) : '';

        $sql .= $orderby.$limit;

        return $sql;
    }

    // --------------------------------------------------------------------

    /**
     * Delete statement
     *
     * Generates a platform-specific delete string from the supplied data
     *
     * @access   public
     * @param    string    the table name
     * @param    array    the where clause
     * @param    string    the limit clause
     * @return   string
     */
    public function _delete($table, $where = array(), $like = array(), $limit = false)
    {
        $conditions = '';

        if (count($where) > 0 OR count($like) > 0)
        {
            $conditions = "\nWHERE ";
            $conditions .= implode("\n", $this->ar_where);

            if (count($where) > 0 && count($like) > 0)
            {
                $conditions .= " AND ";
            }
            $conditions .= implode("\n", $like);
        }

        $limit = ( ! $limit) ? '' : ' LIMIT '.$limit;

        return "DELETE FROM ".$table.$conditions.$limit;
    }

    // --------------------------------------------------------------------

    /**
     * Limit string
     *
     * Generates a platform-specific LIMIT clause
     *
     * @access   public
     * @param    string    the sql query string
     * @param    integer   the number of rows to limit the query to
     * @param    integer   the offset value
     * @return   string
     */
    public function _limit($sql, $limit, $offset)
    {
        if ($offset == 0)
        {
            $offset = '';
        }
        else
        {
            $offset .= ", ";
        }

        return $sql."LIMIT ".$offset.$limit;
    }


} // end class.


/* End of file Pdo_Cubrid.php */
/* Location: ./packages/pdo_cubrid/releases/0.0.1/pdo_cubrid.php */