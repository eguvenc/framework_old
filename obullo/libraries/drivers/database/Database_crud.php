<?php
defined('BASE') or exit('Access Denied!'); 

/**
 * Obullo Framework (c) 2009 - 2012.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 *
 * @package         Obullo
 * @author          Obullo.com  
 * @subpackage      Obullo.database        
 * @copyright       Obullo Team.
 * @license         public
 * @since           Version 1.0
 * @filesource
 */ 

// ------------------------------------------------------------------------

/**
 * Obullo CRUD Class for PDO.
 *
 * @package         Obullo 
 * @subpackage      Obullo.database     
 * @category        Database
 * @version         0.1
 * @version         0.2 added query builder
 * @version         0.3 added prepare 'like' support 
 * @version         0.4 added parent::exec();, added CRUD functions 
 * @version         0.5 added method chaining support. 
 */
 
Class OB_crud  {
                                         
    public $ar_select              = array();
    public $ar_distinct            = FALSE;
    public $ar_from                = array();
    public $ar_join                = array();
    public $ar_where               = array();
    public $ar_like                = array();
    public $ar_groupby             = array();
    public $ar_having              = array();
    public $ar_limit               = FALSE;
    public $ar_offset              = FALSE;
    public $ar_order               = FALSE;
    public $ar_orderby             = array();
    public $ar_set                 = array();    
    public $ar_wherein             = array();
    public $ar_aliased_tables      = array();
    public $ar_store_array         = array();
    
    // Active Record Caching variables
    public $ar_caching             = FALSE;
    public $ar_cache_exists        = array();
    public $ar_cache_select        = array();
    public $ar_cache_from          = array();
    public $ar_cache_join          = array();
    public $ar_cache_where         = array();
    public $ar_cache_like          = array();
    public $ar_cache_groupby       = array();
    public $ar_cache_having        = array();
    public $ar_cache_orderby       = array();
    public $ar_cache_set           = array();    
    
    // Private variables
    public $_protect_identifiers    = TRUE;
    public $_reserved_identifiers   = array('*'); // Identifiers that should NOT be escaped
    
    /**
    * Store $this->_compile_select();
    * result into $sql var
    * 
    * @var string
    */
    public $sql;
    
    /**
    * This is used in _like function
    * we need to know whether like is
    * bind value (:like)
    * 
    * @var mixed
    */
    public $is_like_bind = FALSE;
                                    
    
    public function select($select = '*', $escape = NULL)
    {
        // Set the global value if this was sepecified    
        if (is_bool($escape))
        {
            $this->_protect_identifiers = $escape;
        }
        
        if (is_string($select))
        $select = explode(',', $select);
        
        foreach ($select as $val)
        {
            $val = trim($val);

            if ($val != '')
            {
                $this->ar_select[] = $val;

                if ($this->ar_caching === TRUE)
                {
                    $this->ar_cache_select[] = $val;
                    $this->ar_cache_exists[] = 'select';
                }
            }
        }
        
        return $this;
    }    
    
    // --------------------------------------------------------------------
    
    /**
    * DISTINCT
    *
    * Sets a flag which tells the query string compiler to add DISTINCT
    *
    * @access   public
    * @param    bool
    * @return   object
    */
    public function distinct($val = TRUE)
    {
        $this->ar_distinct = (is_bool($val)) ? $val : TRUE;
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    public function from($from)
    {   
       foreach ((array)$from as $val) // beta 1.0 rc1 changes 
       {
            if (strpos($val, ',') !== FALSE)
            {
                foreach (explode(',', $val) as $v)
                {
                    $v = trim($v);
                    $this->_track_aliases($v);

                    $this->ar_from[] = $this->_protect_identifiers($v, TRUE, NULL, FALSE);
                    
                    if ($this->ar_caching === TRUE)
                    {
                        $this->ar_cache_from[] = $v;
                        $this->ar_cache_exists[] = 'from';
                    }                
                }

            } else {
                
                $val = trim($val);

                // Extract any aliases that might exist.  We use this information
                // in the _protect_identifiers to know whether to add a table prefix 
                $this->_track_aliases($val);

                $this->ar_from[] = $this->_protect_identifiers($val, TRUE, NULL, FALSE);
                
                if ($this->ar_caching === TRUE)
                {
                    $this->ar_cache_from[] = $this->_protect_identifiers($val, TRUE, NULL, FALSE);
                    $this->ar_cache_exists[] = 'from';
                }
                
            }
        
        } // end foreach.
        
        return $this;  
    }
    
    // --------------------------------------------------------------------
                                             
    public function join($table, $cond, $type = '')
    {        
        if ($type != '')
        {
            $type = strtoupper(trim($type));

            if ( ! in_array($type, array('LEFT', 'RIGHT', 'OUTER', 'INNER', 'LEFT OUTER', 'RIGHT OUTER')))
            {
                $type = '';
            }
            else
            {
                $type .= ' ';
            }
        }

        // Extract any aliases that might exist.  We use this information
        // in the _protect_identifiers to know whether to add a table prefix 
        $this->_track_aliases($table);

        // Strip apart the condition and protect the identifiers
        if (preg_match('/([\w\.]+)([\W\s]+)(.+)/', $cond, $match))
        {
            $match[1] = $this->_protect_identifiers($match[1]);
            $match[3] = $this->_protect_identifiers($match[3]);
        
            $cond = $match[1].$match[2].$match[3];        
        }
        
        // Assemble the JOIN statement
        $join = $type.'JOIN '.$this->_protect_identifiers($table, TRUE, NULL, FALSE).' ON '.$cond;

        $this->ar_join[] = $join;
        if ($this->ar_caching === TRUE)
        {
            $this->ar_cache_join[] = $join;
            $this->ar_cache_exists[] = 'join';
        }

        return $this;
    }
    
    // --------------------------------------------------------------------
    
    public function where($key, $value = NULL, $escape = TRUE)
    {
        return $this->_where($key, $value, 'AND ', $escape);
    }
    
    // --------------------------------------------------------------------
    
    public function or_where($key, $value = NULL, $escape = TRUE)
    {
        return $this->_where($key, $value, 'OR ', $escape);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Where
    *
    * Called by where() or orwhere()
    *
    * @access   private
    * @param    mixed
    * @param    mixed
    * @param    string
    * @version  0.1
    * @return   void
    */
    private function _where($key, $value = NULL, $type = 'AND ', $escape = NULL)
    {
        if ( ! is_array($key))
        $key = array($key => $value);
        
        // If the escape value was not set will base it on the global setting
        if ( ! is_bool($escape))
        {
            $escape = $this->_protect_identifiers;
        }
        
        foreach ($key as $k => $v)
        {   
            $prefix = (count($this->ar_where) == 0 AND count($this->ar_cache_where) == 0) ? '' : $type;
            
            if (is_null($v) && ! self::_has_operator($k))
            $k .= ' IS NULL';  // value appears not to have been set, assign the test to IS NULL  
        
            if ( ! is_null($v))
            {
                if ($escape === TRUE)
                {
                    $k = $this->_protect_identifiers($k, FALSE, $escape);
                    
                    $v = ' '.$this->escape($v);
                
                } else  // Obullo changes 
                {   
                    // obullo changes.. 
                    // make sure is it bind value, if not ... 
                    if( strpos($v, ':') === FALSE || strpos($v, ':') > 0)
                    {
                         if(is_string($v))
                         $v = "'{$v}'";  // obullo changes..
                    }
                }
                
                if ( ! self::_has_operator($k))
                {
                    $k .= ' =';
                }
            
            } else 
            {
                $k = $this->_protect_identifiers($k, FALSE, $escape);
            }
             
            $this->ar_where[] = $prefix.$k.$v;
            
            if ($this->ar_caching === TRUE)
            {
                $this->ar_cache_where[] = $prefix.$k.$v;
                $this->ar_cache_exists[] = 'where';
            }
            
        }
        
        return $this;
    }

    // -------------------------------------------------------------------- 
    
    public function where_in($key = NULL, $values = NULL)
    {
        return $this->_where_in($key, $values);
    }
    
    // --------------------------------------------------------------------

    public function or_where_in($key = NULL, $values = NULL)
    {
        return $this->_where_in($key, $values, FALSE, 'OR ');
    }

    // --------------------------------------------------------------------

    public function where_not_in($key = NULL, $values = NULL)
    {
        return $this->_where_in($key, $values, TRUE);
    }
    
    // --------------------------------------------------------------------

    public function or_where_not_in($key = NULL, $values = NULL)
    {
        return $this->_where_in($key, $values, TRUE, 'OR ');
    }
    
    // -------------------------------------------------------------------- 
        
    /**
    * Where_in
    *
    * Called by where_in, where_in_or, where_not_in, where_not_in_or
    *
    * @access   public
    * @param    string    The field to search
    * @param    array     The values searched on
    * @param    boolean   If the statement would be IN or NOT IN
    * @param    string    
    * @return   object
    */
    public function _where_in($key = NULL, $values = NULL, $not = FALSE, $type = 'AND ')
    {
        if ($key === NULL OR $values === NULL)
        return;
        
        if ( ! is_array($values))
        $values = array($values);
        
        $not = ($not) ? ' NOT' : '';

        foreach ($values as $value)
        {
            $this->ar_wherein[] = $this->escape($value);
        }

        $prefix = (count($this->ar_where) == 0) ? '' : $type;
 
        $where_in = $prefix . $this->_protect_identifiers($key) . $not . " IN (" . implode(", ", $this->ar_wherein) . ") ";

        $this->ar_where[] = $where_in;
        if ($this->ar_caching === TRUE)
        {
            $this->ar_cache_where[] = $where_in;
            $this->ar_cache_exists[] = 'where';
        }

        // reset the array for multiple calls
        $this->ar_wherein = array();
        
        return $this;
    }
    
    public function like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'AND ', $side);
    }

    // --------------------------------------------------------------------

    public function not_like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'AND ', $side, 'NOT');
    }
        
    // --------------------------------------------------------------------

    public function or_like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'OR ', $side);
    }

    // --------------------------------------------------------------------

    public function or_not_like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'OR ', $side, 'NOT');
    }
    
    // --------------------------------------------------------------------

    public function orlike($field, $match = '', $side = 'both')
    {
        return $this->or_like($field, $match, $side);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Like
    *
    * Called by like() or orlike()
    *
    * @access   private
    * @param    mixed
    * @param    mixed
    * @param    string
    * @return   void
    */
    private function _like($field, $match = '', $type = 'AND ', $side = 'both', $not = '')
    {
        if ( ! is_array($field))
        $field = array($field => $match);
     
        foreach ($field as $k => $v)
        {
            $k = $this->_protect_identifiers($k);
            
            $prefix = (count($this->ar_like) == 0) ? '' : $type;
        
            // Obullo changes ..
            // if not bind value ... 
            if( strpos($v, ':') === FALSE || strpos($v, ':') > 0) // Obullo rc1 Changes...
            {
               $like_statement = $prefix." $k $not LIKE ".$this->escape_like($v, $side);
            } 
            else 
            {
                // !!IMPORTANT if pdo Bind value used , remove "%" operators..
                // don't do this->db->escape_like
                // because of user must be filter '%like%' values from outside.
               $this->is_like_bind = TRUE;
                
               $like_statement = $prefix." $k $not LIKE ".$v;   
            }
            
            // some platforms require an escape sequence definition for LIKE wildcards
            if ($this->_like_escape_str != '')
            {
                $like_statement = $like_statement.sprintf($this->_like_escape_str, $this->_like_escape_chr);
            }
            
            $this->ar_like[] = $like_statement;
            if ($this->ar_caching === TRUE)
            {
                $this->ar_cache_like[]   = $like_statement;
                $this->ar_cache_exists[] = 'like';
            }
        }
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * GROUP BY
    *
    * @access   public
    * @param    string
    * @return   object
    */
    public function group_by($by)
    {
        if (is_string($by))
        $by = explode(',', $by);
        
        foreach ($by as $val)
        {
            $val = trim($val);
        
            if ($val != '')
            {
                $this->ar_groupby[] = $this->_protect_identifiers($val);
                
                if ($this->ar_caching === TRUE)
                {
                    $this->ar_cache_groupby[] = $this->_protect_identifiers($val);
                    $this->ar_cache_exists[] = 'groupby';
                }
            }
        }
        
        return $this;
    }
    
    // --------------------------------------------------------------------

    public function groupby($by)
    {
        return $this->group_by($by);
    }    
    
    // -------------------------------------------------------------------- 
    
    public function having($key, $value = '', $escape = TRUE)
    {
        return $this->_having($key, $value, 'AND ', $escape);
    }

    // -------------------------------------------------------------------- 

    public function orhaving($key, $value = '', $escape = TRUE)
    {
        return $this->or_having($key, $value, $escape);
    }    
    
    // --------------------------------------------------------------------
    
    public function or_having($key, $value = '', $escape = TRUE)
    {
        return $this->_having($key, $value, 'OR ', $escape);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Sets the HAVING values
    *
    * Called by having() or or_having()
    *
    * @access   private
    * @param    string
    * @param    string
    * @return   object
    */
    private function _having($key, $value = '', $type = 'AND ', $escape = TRUE)
    {
        if ( ! is_array($key))
        {
            $key = array($key => $value);
        }
    
        foreach ($key as $k => $v)
        {
            $prefix = (count($this->ar_having) == 0) ? '' : $type;

            if ($escape === TRUE)
            {
                $k = $this->_protect_identifiers($k);
            }

            if ( ! self::_has_operator($k))
            {
                $k .= ' = ';
            }
            
            if ($v != '')
            {               
                $v = ' '.$this->escape($v);  // obullo changes ..
            }
            
            $this->ar_having[] = $prefix.$k.$v;
            if ($this->ar_caching === TRUE)
            {
                $this->ar_cache_having[] = $prefix.$k.$v;
                $this->ar_cache_exists[] = 'having';
            }
        }
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Sets the ORDER BY value
    *
    * @access   public
    * @param    string
    * @param    string    direction: asc or desc
    * @return   object
    */
    public function order_by($orderby, $direction = '')
    {
        $direction = strtoupper(trim($direction)); 
        if($direction != '')
        {
            switch($direction)
            {
                case 'ASC':
                $direction = ' ASC';    
                break;
                
                case 'DESC':
                $direction = ' DESC';
                break;
                
                default:
                $direction = ' ASC';
            }
        }
                            
        if (strpos($orderby, ',') !== FALSE)
        {
            $temp = array();
            foreach (explode(',', $orderby) as $part)
            {
                $part = trim($part);
                if ( ! in_array($part, $this->ar_aliased_tables))
                {
                    $part = $this->_protect_identifiers(trim($part));
                }
                
                $temp[] = $part;
            }
            
            $orderby = implode(', ', $temp);            
        }
        else
        {
            $orderby = $this->_protect_identifiers($orderby);
        }
    
        $orderby_statement = $orderby.$direction;
        
        $this->ar_orderby[] = $orderby_statement;
        if ($this->ar_caching === TRUE)
        {
            $this->ar_cache_orderby[] = $orderby_statement;
            $this->ar_cache_exists[]  = 'orderby';
        }
        
        return $this;

    }
    
    // --------------------------------------------------------------------
    
    public function orderby($orderby, $direction = '')
    {
        return $this->order_by($orderby, $direction);
    }
    
    // --------------------------------------------------------------------

    public function limit($value, $offset = '')
    {
        $this->ar_limit = $value;

        if ($offset != '')
        $this->ar_offset = $offset;
        
        return $this;
    }
                                          
    // --------------------------------------------------------------------

    public function offset($offset)
    {
        $this->ar_offset = $offset;
        
        return $this;
    }
 
    // --------------------------------------------------------------------
    
    /**
    * The "set" function.  Allows key/value pairs to be set for inserting or updating
    *
    * @access   public
    * @param    mixed
    * @param    string
    * @param    boolean
    * @return   void
    */
    public function set($key, $value = '', $escape = TRUE)
    {
        $key = $this->_object_to_array($key);
        
        if ( ! is_array($key))
        {
            $key = array($key => $value);
        }

        foreach ($key as $k => $v)
        {
            if ($escape === FALSE)                      
            {                                           
                // obullo changes.. 
                // make sure is it bind value, if not ... 
                if( strpos($v, ':') === FALSE || strpos($v, ':') > 0)
                {
                     if(is_string($v))
                     {
                         $v = "'{$v}'";  // obullo changes..
                     }

                }
            
                // obullo changes..
                $this->ar_set[$this->_protect_identifiers($k)] = $v;
            }
            else
            {
                $this->ar_set[$this->_protect_identifiers($k)] = $this->escape($v);
            }
        }
        
        return $this; 
    } 
    
    // --------------------------------------------------------------------
    
    /**
    * Get
    *
    * Compiles the select statement based on the other functions called
    * and runs the query
    *
    * @access   public
    * @param    string    the table
    * @param    string    the limit clause
    * @param    string    the offset clause
    * @return   object | void
    */
    public function get($table = '', $limit = null, $offset = null)
    {
        if ($table != '') 
        {   
            $this->_track_aliases($table);
            $this->from($table);
        }
        
        if ( ! is_null($limit))
        {
            $this->limit($limit, $offset);
        }
            
        $this->sql = $this->_compile_select();
        
        if($this->prepare == FALSE)    // obullo changes ..
        {
            $result = $this->query($this->sql);
            $this->_reset_select();
            
            return $result;
        
        } elseif($this->prepare) // passive mode...
        {
            $this->query($this->sql);  
            $this->_reset_select();
            
            return $this;    // obullo changes ..
        }
 
    }   
    
    // --------------------------------------------------------------------
                                            
    /**
    * Insert
    *
    * Compiles an insert string and runs the query
    *
    * @access   public
    * @param    string   the table to retrieve the results from
    * @param    array    an associative array of insert values
    * @return   PDO exec number of affected rows.
    */
    public function insert($table = '', $set = NULL)
    {    
        if ( ! is_null($set))
        {
            $this->set($set);
        }
        
        if (count($this->ar_set) == 0)
        {
            throw new Exception(lang('db_ac_insert_set_table'));
            
            return FALSE;
        }

        if ($table == '')
        {
            if ( ! isset($this->ar_from[0]))
            {
                throw new Exception(lang('db_ac_insert_set_table'));
                
                return FALSE;
            }
            
            $table = $this->ar_from[0];
        }

        $sql = $this->_insert($this->_protect_identifiers($table, TRUE, NULL, FALSE), array_keys($this->ar_set), array_values($this->ar_set));
        
        $this->_reset_write();
        
        $this->prepare = FALSE;
        
        return $this->exec_query($sql);  // return affected rows.      
    }
    
    // --------------------------------------------------------------------

    /**
     * Replace
     *
     * Compiles an replace into string and runs the query
     *
     * @param	string	the table to replace data into
     * @param	array	an associative array of insert values
     * @return	object
     */
    public function replace($table = '', $set = NULL)
    {
        if ( ! is_null($set))
        {
            $this->set($set);
        }

        if (count($this->ar_set) == 0)
        {
            throw new Exception(lang('db_ac_replace_use_set'));
        }

        if ($table == '')
        {
            if ( ! isset($this->ar_from[0]))
            {
                throw new Exception(lang('db_ac_replace_set_table'));
            }

            $table = $this->ar_from[0];
        }

        $sql = $this->_replace($this->_protect_identifiers($table, TRUE, NULL, FALSE), array_keys($this->ar_set), array_values($this->ar_set));

        $this->_reset_write();
        
        return $this->query($sql);
    }
    
    // --------------------------------------------------------------------
 
    /**
    * Generate an insert string
    *
    * @access   public
    * @param    string   the table upon which the query will be performed
    * @param    array    an associative array data of key/values
    * @return   string        
    */    
    public function insert_string($table, $data)
    {
        $fields = array();
        $values = array();
        
        foreach($data as $key => $val)
        {
            $fields[] = $this->_escape_identifiers($key);
            $values[] = $this->escape($val);
        }
                
        return $this->_insert($table, $fields, $values);
    }    
    
    // --------------------------------------------------------------------
    
    // _insert function in ?_driver.php file.
    
    /**
    * Update
    *
    * Compiles an update string and runs the query
    *
    * @author   Ersin Guvenc
    * @access   public
    * @param    string   the table to retrieve the results from
    * @param    array    an associative array of update values
    * @param    mixed    the where clause
    * @return   PDO exec number of affected rows
    */
    public function update($table = '', $set = NULL, $where = NULL, $limit = NULL)
    {
        // Combine any cached components with the current statements
        $this->_merge_cache();
        
        if ( ! is_null($set))
        {
            $this->set($set);
        }
    
        if (count($this->ar_set) == 0)
        {
            throw new Exception(lang('db_ac_update_set_table'));
            
            return FALSE;
        }
                                         
        if ($table == '')
        {
            if ( ! isset($this->ar_from[0]))
            {
                throw new Exception(lang('db_ac_update_set_table')); 
                
                return FALSE;
            }
            
            $table = $this->ar_from[0];
        }
        
        if ($where != NULL)
        {
            $this->where($where);
        }
        
        if ($limit != NULL)
        {
            $this->limit($limit);   
        }       
        
        $sql = $this->_update($this->_protect_identifiers($table, TRUE, NULL, FALSE), $this->ar_set, $this->ar_where, $this->ar_orderby, $this->ar_limit);
                 
        $this->_reset_write();
        
        $this->prepare = FALSE;
        
        return $this->exec_query($sql);  // return number of affected rows.  
    }
    
    // _update function in ?_driver.php file.
    
    // --------------------------------------------------------------------
                    
    /**
    * Generate an update string
    *
    * @access   public
    * @param    string   the table upon which the query will be performed
    * @param    array    an associative array data of key/values
    * @param    mixed    the "where" statement
    * @return   string        
    */    
    public function update_string($table, $data, $where)
    {
        if ($where == '')
        {
            return FALSE;
        }
                    
        $fields = array();
        foreach($data as $key => $val)
        {
            $fields[$this->_protect_identifiers($key)] = $this->escape($val);
        }

        if ( ! is_array($where))
        {
            $dest = array($where);
        }
        else
        {
            $dest = array();
            foreach ($where as $key => $val)
            {
                $prefix = (count($dest) == 0) ? '' : ' AND ';
    
                if ($val !== '')
                {
                    if ( ! self::_has_operator($key))
                    {
                        $key .= ' =';
                    }
                
                    $val = ' '.$this->escape($val);
                }
                            
                $dest[] = $prefix.$key.$val;
            }
        }        

        return $this->_update($this->_protect_identifiers($table, TRUE, NULL, FALSE), $fields, $dest);
    }
    
    // --------------------------------------------------------------------
    
    // _update function in ?_driver.php file.
    
    /**
    * Delete
    *
    * Compiles a delete string and runs the query
    *
    * @access   public
    * @param    mixed    the table(s) to delete from. String or array
    * @param    mixed    the where clause
    * @param    mixed    the limit clause
    * @param    boolean
    * @return   object
    */
    public function delete($table = '', $where = '', $limit = NULL, $reset_data = TRUE)
    {
        // Combine any cached components with the current statements
        $this->_merge_cache();

        if ($table == '')
        {
            if ( ! isset($this->ar_from[0]))
            {
                throw new Exception(lang('db_active_record_set_table'));
                
                return FALSE;
            }

            $table = $this->ar_from[0];
        }
        elseif (is_array($table))
        {
            foreach($table as $single_table)
            $this->delete($single_table, $where, $limit, FALSE);
        
            $this->_reset_write();
            return;
        } else 
        {
            $table = $this->_protect_identifiers($table, TRUE, NULL, FALSE);
        }

        if ($where != '')
        $this->where($where);
        
        if ($limit != NULL)
        $this->limit($limit);
        
        if (count($this->ar_where) == 0 && count($this->ar_wherein) == 0 && count($this->ar_like) == 0)
        {
            throw new Exception(lang('db_active_record_delete'));
            
            return FALSE;
        }        

        $sql = $this->_delete($table, $this->ar_where, $this->ar_like, $this->ar_limit);
        
        if ($reset_data)
        $this->_reset_write();
        
        $this->prepare = FALSE;
        
        return $this->exec_query($sql); // return number of  affected rows
    
    } 
    
    // _delete function in ?_driver.php file.
    
    /**
    * Track Aliases
    *
    * Used to track SQL statements written with aliased tables.
    *
    * @access    private
    * @param     string    The table to inspect
    * @return    string
    */    
    private function _track_aliases($table)
    {
        if (is_array($table))
        {
            foreach ($table as $t)
            {
                $this->_track_aliases($t);
            }
            return;
        }
        
        // Does the string contain a comma?  If so, we need to separate
        // the string into discreet statements
        if (strpos($table, ',') !== FALSE)
        {
            return $this->_track_aliases(explode(',', $table));
        }
    
        // if a table alias is used we can recognize it by a space
        if (strpos($table, " ") !== FALSE)
        {
            // if the alias is written with the AS keyword, remove it
            $table = preg_replace('/ AS /i', ' ', $table);
            
            // Grab the alias
            $table = trim(strrchr($table, " "));
            
            // Store the alias, if it doesn't already exist
            if ( ! in_array($table, $this->ar_aliased_tables))
            {
                $this->ar_aliased_tables[] = $table;
            }
        }
    }
    
    // --------------------------------------------------------------------

    /**
    * Compile the SELECT statement
    *
    * Generates a query string based on which functions were used.
    * Should not be called directly.  The get() function calls it.
    *
    * @access    private
    * @return    string
    */
    private function _compile_select($select_override = FALSE)
    {
        // Combine any cached components with the current statements
        $this->_merge_cache();

        // ----------------------------------------------------------------
        
        // Write the "select" portion of the query

        if ($select_override !== FALSE)
        {
            $sql = $select_override;
        }
        else
        {
            $sql = ( ! $this->ar_distinct) ? 'SELECT ' : 'SELECT DISTINCT ';
        
            if (count($this->ar_select) == 0)
            {
                $sql .= '*';        
            }
            else
            {                
                // Cycle through the "select" portion of the query and prep each column name.
                // The reason we protect identifiers here rather then in the select() function
                // is because until the user calls the from() function we don't know if there are aliases
                foreach ($this->ar_select as $key => $val)
                {
                    $this->ar_select[$key] = $this->_protect_identifiers($val);
                }
                
                $sql .= implode(', ', $this->ar_select);
            }
        }

        // ----------------------------------------------------------------
        
        // Write the "FROM" portion of the query

        if (count($this->ar_from) > 0)
        {
            $sql .= "\nFROM ";

            $sql .= $this->_from_tables($this->ar_from);
        }

        // ----------------------------------------------------------------
        
        // Write the "JOIN" portion of the query

        if (count($this->ar_join) > 0)
        {
            $sql .= "\n";

            $sql .= implode("\n", $this->ar_join);
        }

        // ----------------------------------------------------------------
        
        // Write the "WHERE" portion of the query

        if (count($this->ar_where) > 0 OR count($this->ar_like) > 0)
        {
            $sql .= "\n";

            $sql .= "WHERE ";
        }

        $sql .= implode("\n", $this->ar_where);

        // ----------------------------------------------------------------
        
        // Write the "LIKE" portion of the query
    
        if (count($this->ar_like) > 0)
        {
            if (count($this->ar_where) > 0)
            {
                $sql .= "\nAND ";
            }

            $sql .= implode("\n", $this->ar_like);
        }

        // ----------------------------------------------------------------
                             
        // Write the "GROUP BY" portion of the query
    
        if (count($this->ar_groupby) > 0)
        {
            $sql .= "\nGROUP BY ";
            
            $sql .= implode(', ', $this->ar_groupby);
        }

        // ----------------------------------------------------------------
        
        // Write the "HAVING" portion of the query
        
        if (count($this->ar_having) > 0)
        {
            $sql .= "\nHAVING ";
            $sql .= implode("\n", $this->ar_having);
        }

        // ----------------------------------------------------------------
        
        // Write the "ORDER BY" portion of the query

        if (count($this->ar_orderby) > 0)
        {
            $sql .= "\nORDER BY ";
            $sql .= implode(', ', $this->ar_orderby);
            
            if ($this->ar_order !== FALSE)
            {
                $sql .= ($this->ar_order == 'desc') ? ' DESC' : ' ASC';
            }        
        }

        // ----------------------------------------------------------------
        
        // Write the "LIMIT" portion of the query
        
        if (is_numeric($this->ar_limit))
        {
            $sql .= "\n";
            $sql = $this->_limit($sql, $this->ar_limit, $this->ar_offset);
        }

        return $sql;
    }

    // _from_tables  function in to ?_driver.php file.
    
    // --------------------------------------------------------------------

    /**
    * Object to Array
    *
    * Takes an object as input and converts the class variables to array key/vals
    *
    * @access   public
    * @param    object
    * @return   array
    */
    public function _object_to_array($object)
    {
        if ( ! is_object($object))
        {
            return $object;
        }
        
        $array = array();
        foreach (get_object_vars($object) as $key => $val)
        {
            // There are some built in keys we need to ignore for this conversion
            if ( ! is_object($val) && ! is_array($val) )
            {
                $array[$key] = $val;
            }
        }
    
        return $array;
    }
    
    /**
    * Start Cache
    *
    * Starts AR caching
    *
    * @access    public
    * @return    void
    */        
    public function start_cache()
    {
        $this->ar_caching = TRUE;
    }

    // --------------------------------------------------------------------

    /**
    * Stop Cache
    *
    * Stops AR caching
    *
    * @access    public
    * @return    void
    */        
    public function stop_cache()
    {
        $this->ar_caching = FALSE;
    }

    // --------------------------------------------------------------------

    /**
    * Flush Cache
    *
    * Empties the AR cache
    *
    * @access    public
    * @return    void
    */    
    public function flush_cache()
    {    
        $this->_reset_run(
                            array(
                                    'ar_cache_select'   => array(), 
                                    'ar_cache_from'     => array(), 
                                    'ar_cache_join'     => array(),
                                    'ar_cache_where'    => array(), 
                                    'ar_cache_like'     => array(), 
                                    'ar_cache_groupby'  => array(), 
                                    'ar_cache_having'   => array(), 
                                    'ar_cache_orderby'  => array(), 
                                    'ar_cache_set'      => array(),
                                    'ar_cache_exists'   => array()
                                )
                            );    
    }

    // --------------------------------------------------------------------

    /**
    * Merge Cache
    *
    * When called, this function merges any cached AR arrays with 
    * locally called ones.
    *
    * @access    private
    * @return    void
    */
    private function _merge_cache()
    {
        if (count($this->ar_cache_exists) == 0)
        return;

        foreach ($this->ar_cache_exists as $val)
        {
            $ar_variable    = 'ar_'.$val;
            $ar_cache_var   = 'ar_cache_'.$val;

            if (count($this->$ar_cache_var) == 0)
            continue;
    
            $this->$ar_variable = array_unique(array_merge($this->$ar_cache_var, $this->$ar_variable));
        }
    }

    // --------------------------------------------------------------------
        
    /**
    * Resets the active record values.  Called by the get() function
    *
    * @access   private
    * @param    array    An array of fields to reset
    * @return   void
    */
    private function _reset_run($ar_reset_items)
    {
        foreach ($ar_reset_items as $item => $default_value)
        {
            if ( ! in_array($item, $this->ar_store_array))
            $this->$item = $default_value;
        }
    }
 
   // --------------------------------------------------------------------

    /**
    * Resets the active record values.  Called by the get() function
    *
    * @access    private
    * @return    void
    */
    public function _reset_select()
    {
        $ar_reset_items = array(
                                'ar_select'         => array(), 
                                'ar_from'           => array(), 
                                'ar_join'           => array(), 
                                'ar_where'          => array(), 
                                'ar_like'           => array(), 
                                'ar_groupby'        => array(), 
                                'ar_having'         => array(), 
                                'ar_orderby'        => array(), 
                                'ar_wherein'        => array(), 
                                'ar_aliased_tables' => array(),
                                'ar_distinct'       => FALSE, 
                                'ar_limit'          => FALSE, 
                                'ar_offset'         => FALSE, 
                                'ar_order'          => FALSE,
                            );
        
        $this->_reset_run($ar_reset_items);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Resets the active record "write" values.
    *
    * Called by the insert() update() and delete() functions
    *
    * @access    private
    * @return    void
    */
    private function _reset_write()
    {    
        $ar_reset_items = array(
                                'ar_set'        => array(), 
                                'ar_from'       => array(), 
                                'ar_where'      => array(), 
                                'ar_like'       => array(),
                                'ar_orderby'    => array(), 
                                'ar_limit'      => FALSE, 
                                'ar_order'      => FALSE
                                );

        $this->_reset_run($ar_reset_items);
    }

    // --------------------------------------------------------------------

    /**
    * Tests whether the string has an SQL operator
    *
    * @access   private
    * @param    string
    * @return   bool
    */ 
    private static function _has_operator($str)
    {
        $str = trim($str);
        if ( ! preg_match("/(\s|<|>|!|=|is null|is not null)/i", $str))
        {
            return FALSE;
        }

        return TRUE;
    }
    
     // --------------------------------------------------------------------

    /**
    * Protect Identifiers
    *
    * This function adds backticks if appropriate based on db type
    *
    * @access   private
    * @param    mixed    the item to escape
    * @return   mixed    the item with backticks
    */
    private function protect_identifiers($item, $prefix_single = FALSE)
    {
        return $this->_protect_identifiers($item, $prefix_single);
    }

    // --------------------------------------------------------------------

    /**
    * Protect Identifiers
    *
    * This function is used extensively by the Active Record class, and by
    * a couple functions in this class.
    * It takes a column or table name (optionally with an alias) and inserts
    * the table prefix onto it.  Some logic is necessary in order to deal with
    * column names that include the path.  Consider a query like this:
    *
    * SELECT * FROM hostname.database.table.column AS c FROM hostname.database.table
    *
    * Or a query with aliasing:
    *
    * SELECT m.member_id, m.member_name FROM members AS m
    *
    * Since the column name can include up to four segments (host, DB, table, column)
    * or also have an alias prefix, we need to do a bit of work to figure this out and
    * insert the table prefix (if it exists) in the proper position, and escape only
    * the correct identifiers.
    *
    * @access   private
    * @param    string
    * @param    bool
    * @param    mixed
    * @param    bool
    * @return   string
    */
    public function _protect_identifiers($item, $prefix_single = FALSE, $protect_identifiers = NULL, $field_exists = TRUE)
    {
        if ( ! is_bool($protect_identifiers))
        {
            $protect_identifiers = $this->_protect_identifiers;
        }

        if (is_array($item))
        {
            $escaped_array = array();

            foreach($item as $k => $v)
            {
                $escaped_array[$this->_protect_identifiers($k)] = $this->_protect_identifiers($v);
            }

            return $escaped_array;
        }

        // Convert tabs or multiple spaces into single spaces
        $item = preg_replace('/[\t ]+/', ' ', $item);

        // If the item has an alias declaration we remove it and set it aside.
        // Basically we remove everything to the right of the first space
        $alias = '';
        if (strpos($item, ' ') !== FALSE)
        {
            $alias = strstr($item, " ");
            $item  = substr($item, 0, - strlen($alias));
        }

        // This is basically a bug fix for queries that use MAX, MIN, etc.
        // If a parenthesis is found we know that we do not need to
        // escape the data or add a prefix.  There's probably a more graceful
        // way to deal with this, but I'm not thinking of it -- Rick
        if (strpos($item, '(') !== FALSE)
        {
            return $item.$alias;
        }

        // Break the string apart if it contains periods, then insert the table prefix
        // in the correct location, assuming the period doesn't indicate that we're dealing
        // with an alias. While we're at it, we will escape the components
        if (strpos($item, '.') !== FALSE)
        {
            $parts = explode('.', $item);

            // Does the first segment of the exploded item match
            // one of the aliases previously identified?  If so,
            // we have nothing more to do other than escape the item
            if (in_array($parts[0], $this->ar_aliased_tables))
            {
                if ($protect_identifiers === TRUE)
                {
                    foreach ($parts as $key => $val)
                    {
                        if ( ! in_array($val, $this->_reserved_identifiers))
                        {
                            $parts[$key] = $this->_escape_identifiers($val);
                        }
                    }

                    $item = implode('.', $parts);
                }
                return $item.$alias;
            }

            // Is there a table prefix defined in the config file?  If not, no need to do anything
            if ($this->dbprefix != '')
            {
                // We now add the table prefix based on some logic.
                // Do we have 4 segments (hostname.database.table.column)?
                // If so, we add the table prefix to the column name in the 3rd segment.
                if (isset($parts[3]))
                {
                    $i = 2;
                }
                // Do we have 3 segments (database.table.column)?
                // If so, we add the table prefix to the column name in 2nd position
                elseif (isset($parts[2]))
                {
                    $i = 1;
                }
                // Do we have 2 segments (table.column)?
                // If so, we add the table prefix to the column name in 1st segment
                else
                {
                    $i = 0;
                }

                // This flag is set when the supplied $item does not contain a field name.
                // This can happen when this function is being called from a JOIN.
                if ($field_exists == FALSE)
                {
                    $i++;
                }

                // Verify table prefix and replace if necessary
                if ($this->swap_pre != '' && strncmp($parts[$i], $this->swap_pre, strlen($this->swap_pre)) === 0)
                {
                    $parts[$i] = preg_replace("/^".$this->swap_pre."(\S+?)/", $this->dbprefix."\\1", $parts[$i]);
                }

                // We only add the table prefix if it does not already exist
                if (substr($parts[$i], 0, strlen($this->dbprefix)) != $this->dbprefix)
                {
                    $parts[$i] = $this->dbprefix.$parts[$i];
                }

                // Put the parts back together
                $item = implode('.', $parts);
            }

            if ($protect_identifiers === TRUE)
            {
                $item = $this->_escape_identifiers($item);
            }

            return $item.$alias;
        }

        // Is there a table prefix?  If not, no need to insert it
        if ($this->dbprefix != '')
        {
            // Verify table prefix and replace if necessary
            if ($this->swap_pre != '' && strncmp($item, $this->swap_pre, strlen($this->swap_pre)) === 0)
            {
                $item = preg_replace("/^".$this->swap_pre."(\S+?)/", $this->dbprefix."\\1", $item);
            }

            // Do we prefix an item with no segments?
            if ($prefix_single == TRUE AND substr($item, 0, strlen($this->dbprefix)) != $this->dbprefix)
            {
                $item = $this->dbprefix.$item;
            }
        }

        if ($protect_identifiers === TRUE AND ! in_array($item, $this->_reserved_identifiers))
        {
            $item = $this->_escape_identifiers($item);
        }

        return $item.$alias;
    }
                 
                                  
}

/* End of file Database_crud.php */
/* Location: .obullo/libraries/drivers/database/Database_crud.php */