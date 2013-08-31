<?php

/**
 * Firebird Database Adapter Class
 *
 * @package       Ob
 * @subpackage    pdo_firebird
 * @category      database
 * @link                              
 */

Class Pdo_Firebird extends Database_Pdo\Src\Database_Adapter
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
        
        $dsn = empty($this->dsn) ? 'firebird:dbname='.$this->database : $this->dsn;
             
        $this->_pdo = $this->pdoConnect($dsn, $this->username, $this->password, $this->options);
        
        // We set exception attribute for always showing the pdo exceptions errors. (ersin)
        $this->_conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
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
            return $str;
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
     * Escape Table Name
     *
     * This function adds backticks if the table name has a period
     * in it. Some DBs will get cranky unless periods are escaped
     *
     * @access  private
     * @param   string  the table name
     * @return  string
     */
    public function _escape_table($table)
    {
        if (stristr($table, '.'))
        {
            $table = preg_replace("/\./", "`.`", $table);
        }

        return $table;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Insert statement
     *
     * Generates a platform-specific insert string from the supplied data
     *
     * @access  public
     * @param   string  the table name
     * @param   array   the insert keys
     * @param   array   the insert values
     * @return  string
     */
    public function _insert($table, $keys, $values)
    {
        return "INSERT INTO ".$this->_escape_table($table)." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
    }
    
    // --------------------------------------------------------------------

    /**
     * Update statement
     *
     * Generates a platform-specific update string from the supplied data
     *
     * @access  public
     * @param   string  the table name
     * @param   array   the update data
     * @param   array   the where clause
     * @return  string
     */
    public function _update($table, $where = array(), $like = array(), $limit = false)
    {
        foreach($values as $key => $val)
        {
            $valstr[] = $key." = ".$val;
        }

        return "UPDATE ".$this->_escape_table($table)." SET ".implode(', ', $valstr)." WHERE ".implode(" ", $where);
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
     * @return   string
     */
    public function _delete($table, $where = array(), $like = array(), $limit = false)
    {
        return "DELETE FROM ".$this->_escape_table($table)." WHERE ".implode(" ", $where);
    }

    // --------------------------------------------------------------------

    /**
     * Limit string
     *
     * Generates a platform-specific LIMIT clause
     *
     * @access  public
     * @param   string  the sql query string
     * @param   integer the number of rows to limit the query to
     * @param   integer the offset value
     * @return  string
     */
    public function _limit($sql, $limit, $offset)
    {
        $partial_sql = ltrim($sql, 'SELECTselect');

        if ($offset != 0)
        {
            $newsql = 'SELECT FIRST ' . $limit . ' SKIP ' . $offset . ' ' . $partial_sql;
        }
        else
        {
            $newsql = 'SELECT FIRST ' . $limit . ' ' . $partial_sql;
        }

        // remember that we used limits
        // $this->limit_used = true;

        return $newsql;
    }


} // end class.


/* End of file Pdo_Firebird.php */
/* Location: ./ob/pdo_firebird/releases/0.0.1/pdo_firebird.php */