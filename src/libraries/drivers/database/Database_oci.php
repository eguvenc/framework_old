<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 *
 * @package         Obullo
 * @author          Obullo.com  
 * @subpackage      Obullo.database        
 * @copyright       Copyright (c) 2009 Ersin Guvenc.
 * @license         public
 * @since           Version 1.0
 * @filesource
 */ 
// ------------------------------------------------------------------------

/**
 * OCI (Oracle) Database Adapter Class
 *
 * @package       Obullo
 * @subpackage    Drivers
 * @category      Database
 * @author        Ersin Guvenc 
 * @link                              
 */

Class OB_Database_oci extends OB_Database_adapter
{
    /**
    * The character used for escaping
    * 
    * @var string
    */
    public $_escape_char = '"';
    
    // clause and character used for LIKE escape sequences
    public $_like_escape_str = " escape '%s' ";
    public $_like_escape_chr = '!';
    
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
        
        $port    = empty($this->dbh_port) ? '' : ':'.$this->dbh_port;
        $charset = empty($this->char_set) ? '' : ';charset='.$this->char_set; 
        $dsn     = empty($this->dsn) ? 'oci:dbname='.$this->hostname.$port.'/'.$this->database.$charset : $this->dsn;
        $this->_pdo  = $this->pdo_connect($dsn, $this->username, $this->password, $this->options);
        
        // We set exception attribute for always showing the pdo exceptions errors. (ersin)
        $this->_conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    } 
                
    // --------------------------------------------------------------------
    
    /**
     * Escape String
     *
     * @access  public
     * @param   string
     * @param   bool    whether or not the string will be used in a LIKE condition
     * @return  string
     */
    public function escape_str($str, $like = FALSE, $side = 'both')
    {
        if (is_array($str))
        {
            foreach($str as $key => $val)
            {
                $str[$key] = $this->escape_str($val, $like);
            }
           
            return $str;
        }

        loader::helper('ob/security');
        
        $str = _remove_invisible_characters($str);
        
        // escape LIKE condition wildcards
        if ($like === TRUE)
        {
            $str = str_replace( array('%', '_', $this->_like_escape_chr),
                                array($this->_like_escape_chr.'%', $this->_like_escape_chr.'_', 
                                $this->_like_escape_chr.$this->_like_escape_chr), $str);
            
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
            if($this->prepare == TRUE AND $this->is_like_bind)   
            return $str;
        } 
        
        // make sure is it bind value, if not ...
        if($this->prepare === TRUE)
        {
            if(strpos($str, ':') === FALSE)
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
    * Quote a string.
    * Most PDO drivers have an implementation for the quote() method,
    * but the Oracle OCI driver not. From Zend.
    *
    * @param   string $value    Raw string
    * @return  string           Quoted string
    */
    protected function quote($value, $type = NULL)
    {
        if (is_int($value) || is_float($value))
        {
            return $value;
        }
        
        $value = str_replace("'", "''", $value);
        return "'" . addcslashes($value, "\000\n\r\\\032") . "'";
    }
    
    // -------------------------------------------------------------------- 
    
    /**
     * Escape the SQL Identifiers
     *
     * This function escapes column and table names
     *
     * @access    private
     * @param    string
     * @return    string
     */
    public function _escape_identifiers($item)
    {
        if ($this->_escape_char == '')
        {
            return $item;
        }

        foreach ($this->_reserved_identifiers as $id)
        {
            if (strpos($item, '.'.$id) !== FALSE)
            {
                $str = $this->_escape_char. str_replace('.', $this->_escape_char.'.', $item);  
                
                // remove duplicates if the user already included the escape
                return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
            }        
        }
    
        if (strpos($item, '.') !== FALSE)
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
    public function _from_tables($tables)
    {
        if ( ! is_array($tables))
        {
            $tables = array($tables);
        }
        
        return implode(', ', $tables);
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
        return "INSERT INTO ".$table." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
    }
    
    // --------------------------------------------------------------------

    /**
     * Update statement
     *
     * Generates a platform-specific update string from the supplied data
     *
     * @access   public
     * @param    string    the table name
     * @param    array    the update data
     * @param    array    the where clause
     * @param    array    the orderby clause
     * @param    array    the limit clause
     * @return   string
     */
    public function _update($table, $values, $where, $orderby = array(), $limit = FALSE)
    {
        foreach($values as $key => $val)
        {
            $valstr[] = $key." = ".$val;
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
    public function _delete($table, $where = array(), $like = array(), $limit = FALSE)
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
     * @access  public
     * @param   string  the sql query string
     * @param   integer the number of rows to limit the query to
     * @param   integer the offset value
     * @return  string
     */
    public function _limit($sql, $limit, $offset)
    {
        $limit = $offset + $limit;
        $newsql = "SELECT * FROM (select inner_query.*, rownum rnum FROM ($sql) inner_query WHERE rownum < $limit)";

        if ($offset != 0)
        {
            $newsql .= " WHERE rnum >= $offset";
        }

        // remember that we used limits
        // $this->limit_used = TRUE;

        return $newsql;
    }


} // end class.


/* End of file Database_oci.php */
/* Location: ./obullo/libraries/drivers/database/Database_oci.php */