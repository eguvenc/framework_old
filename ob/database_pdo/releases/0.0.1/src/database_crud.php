<?php
namespace Database_Pdo\Src;

/**
 * CRUD ( CREATE - READ - UPDATE - DELETE ) Class for ** PDO.
 *
 * @package         Obullo 
 * @subpackage      Obullo.database     
 * @category        Database
 * @version         0.1
 */
 
Class Database_Crud {
                                         
    public $ar_select              = array();
    public $ar_distinct            = false;
    public $ar_from                = array();
    public $ar_join                = array();
    public $ar_where               = array();
    public $ar_like                = array();
    public $ar_groupby             = array();
    public $ar_having              = array();
    public $ar_limit               = false;
    public $ar_offset              = false;
    public $ar_order               = false;
    public $ar_orderby             = array();
    public $ar_set                 = array();    
    public $ar_wherein             = array();
    public $ar_aliased_tables      = array();
    public $ar_store_array         = array();
    
    // Active Record Caching variables
    public $ar_caching             = false;
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
    public $_protect_identifiers    = true;
    public $_reserved_identifiers   = array('*'); // Identifiers that should NOT be escaped
    
    /**
    * Store $this->_compileSelect();
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
    public $is_like_bind = false;
                                    
    
    public function select($select = '*', $escape = null)
    {
        // Set the global value if this was sepecified    
        if (is_bool($escape))
        {
            $this->_protect_identifiers = $escape;
        }
        
        if (is_string($select))
        {
            $select = explode(',', $select);
        }
        
        foreach ($select as $val)
        {
            $val = trim($val);

            if ($val != '')
            {
                $this->ar_select[] = $val;

                if ($this->ar_caching === true)
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
    public function distinct($val = true)
    {
        $this->ar_distinct = (is_bool($val)) ? $val : true;
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    public function from($from)
    {   
       foreach ((array)$from as $val) // beta 1.0 rc1 changes 
       {
            if (strpos($val, ',') !== false)
            {
                foreach (explode(',', $val) as $v)
                {
                    $v = trim($v);
                    $this->_trackAliases($v);

                    $this->ar_from[] = $this->_protectIdentifiers($v, true, null, false);
                    
                    if ($this->ar_caching === true)
                    {
                        $this->ar_cache_from[] = $v;
                        $this->ar_cache_exists[] = 'from';
                    }                
                }

            } else {
                
                $val = trim($val);

                // Extract any aliases that might exist.  We use this information
                // in the _protect_identifiers to know whether to add a table prefix 
                $this->_trackAliases($val);

                $this->ar_from[] = $this->_protectIdentifiers($val, true, null, false);
                
                if ($this->ar_caching === true)
                {
                    $this->ar_cache_from[] = $this->_protectIdentifiers($val, true, null, false);
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
        $this->_trackAliases($table);

        // Strip apart the condition and protect the identifiers
        if (preg_match('/([\w\.]+)([\W\s]+)(.+)/', $cond, $match))
        {
            $match[1] = $this->_protectIdentifiers($match[1]);
            $match[3] = $this->_protectIdentifiers($match[3]);
        
            $cond = $match[1].$match[2].$match[3];        
        }
        
        // Assemble the JOIN statement
        $join = $type.'JOIN '.$this->_protectIdentifiers($table, true, null, false).' ON '.$cond;

        $this->ar_join[] = $join;
        if ($this->ar_caching === true)
        {
            $this->ar_cache_join[] = $join;
            $this->ar_cache_exists[] = 'join';
        }

        return $this;
    }
    
    // --------------------------------------------------------------------
    
    public function where($key, $value = null, $escape = true)
    {
        return $this->_where($key, $value, 'AND ', $escape);
    }
    
    // --------------------------------------------------------------------
    
    public function orWhere($key, $value = null, $escape = true)
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
    private function _where($key, $value = null, $type = 'AND ', $escape = null)
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
            
            if (is_null($v) && ! self::_hasOperator($k))
            $k .= ' IS null';  // value appears not to have been set, assign the test to IS null  
        
            if ( ! is_null($v))
            {
                if ($escape === true)
                {
                    $k = $this->_protectIdentifiers($k, false, $escape);
                    
                    $v = ' '.$this->escape($v);
                
                } else  // Obullo changes 
                {   
                    // obullo changes.. 
                    // make sure is it bind value, if not ... 
                    if( strpos($v, ':') === false || strpos($v, ':') > 0)
                    {
                         if(is_string($v))
                         {
                             $v = "'{$v}'";  // obullo PDO changes..
                         }
                    }
                }
                
                if ( ! self::_hasOperator($k))
                {
                    $k .= ' =';
                }
            
            } else 
            {
                $k = $this->_protectIdentifiers($k, false, $escape);
            }
             
            $this->ar_where[] = $prefix.$k.$v;
            
            if ($this->ar_caching === true)
            {
                $this->ar_cache_where[] = $prefix.$k.$v;
                $this->ar_cache_exists[] = 'where';
            }
            
        }
        
        return ($this);
    }

    // -------------------------------------------------------------------- 
    
    public function whereIn($key = null, $values = null)
    {
        return $this->_whereIn($key, $values);
    }
    
    // --------------------------------------------------------------------

    public function orWhereIn($key = null, $values = null)
    {
        return $this->_whereIn($key, $values, false, 'OR ');
    }

    // --------------------------------------------------------------------

    public function whereNotIn($key = null, $values = null)
    {
        return $this->_whereIn($key, $values, true);
    }
    
    // --------------------------------------------------------------------

    public function orWhereNotIn($key = null, $values = null)
    {
        return $this->_whereIn($key, $values, true, 'OR ');
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
    public function _whereIn($key = null, $values = null, $not = false, $type = 'AND ')
    {
        if ($key === null OR $values === null)
        return;
        
        if ( ! is_array($values))
        {
            $values = array($values);
        }
        
        $not = ($not) ? ' NOT' : '';

        foreach ($values as $value)
        {
            $this->ar_wherein[] = $this->escape($value);
        }

        $prefix = (count($this->ar_where) == 0) ? '' : $type;
 
        $where_in = $prefix . $this->_protectIdentifiers($key) . $not . " IN (" . implode(", ", $this->ar_wherein) . ") ";

        $this->ar_where[] = $where_in;
        if ($this->ar_caching === true)
        {
            $this->ar_cache_where[] = $where_in;
            $this->ar_cache_exists[] = 'where';
        }

        // reset the array for multiple calls
        $this->ar_wherein = array();
        
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    public function like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'AND ', $side);
    }

    // --------------------------------------------------------------------

    public function notLike($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'AND ', $side, 'NOT');
    }
        
    // --------------------------------------------------------------------

    public function orLike($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'OR ', $side);
    }

    // --------------------------------------------------------------------

    public function orNotLike($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'OR ', $side, 'NOT');
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
        {
            $field = array($field => $match);
        }
     
        foreach ($field as $k => $v)
        {
            $k = $this->_protectIdentifiers($k);
            
            $prefix = (count($this->ar_like) == 0) ? '' : $type;
        
            // Obullo changes ..
            // if not bind value ... 
            if( strpos($v, ':') === false || strpos($v, ':') > 0) // Obullo rc1 Changes...
            {
               $like_statement = $prefix." $k $not LIKE ".$this->escapeLike($v, $side);
            } 
            else 
            {
                // !!IMPORTANT if pdo Bind value used , remove "%" operators..
                // don't do this->db->escape_like
                // because of user must be filter '%like%' values from outside.
               $this->is_like_bind = true;
                
               $like_statement = $prefix." $k $not LIKE ".$v;   
            }
            
            // some platforms require an escape sequence definition for LIKE wildcards
            if ($this->_like_escape_str != '')
            {
                $like_statement = $like_statement.sprintf($this->_like_escape_str, $this->_like_escape_chr);
            }
            
            $this->ar_like[] = $like_statement;
            
            if ($this->ar_caching === true)
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
    public function groupBy($by)
    {
        if (is_string($by))
        {
            $by = explode(',', $by);
        }
        
        foreach ($by as $val)
        {
            $val = trim($val);
        
            if ($val != '')
            {
                $this->ar_groupby[] = $this->_protectIdentifiers($val);
                
                if ($this->ar_caching === true)
                {
                    $this->ar_cache_groupby[] = $this->_protectIdentifiers($val);
                    $this->ar_cache_exists[] = 'groupby';
                }
            }
        }
        
        return $this;
    }
    
    // -------------------------------------------------------------------- 
    
    public function having($key, $value = '', $escape = true)
    {
        return $this->_having($key, $value, 'AND ', $escape);
    }
    
    // --------------------------------------------------------------------
    
    public function orHaving($key, $value = '', $escape = true)
    {
        return $this->_having($key, $value, 'OR ', $escape);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Sets the HAVING values
    *
    * Called by having() or orHaving()
    *
    * @access   private
    * @param    string
    * @param    string
    * @return   object
    */
    private function _having($key, $value = '', $type = 'AND ', $escape = true)
    {
        if ( ! is_array($key))
        {
            $key = array($key => $value);
        }
    
        foreach ($key as $k => $v)
        {
            $prefix = (count($this->ar_having) == 0) ? '' : $type;

            if ($escape === true)
            {
                $k = $this->_protectIdentifiers($k);
            }

            if ( ! self::_hasOperator($k))
            {
                $k .= ' = ';
            }
            
            if ($v != '')
            {               
                $v = ' '.$this->escape($v);  // obullo changes ..
            }
            
            $this->ar_having[] = $prefix.$k.$v;
            if ($this->ar_caching === true)
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
    public function orderBy($orderby, $direction = '')
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
                            
        if (strpos($orderby, ',') !== false)
        {
            $temp = array();
            foreach (explode(',', $orderby) as $part)
            {
                $part = trim($part);
                if ( ! in_array($part, $this->ar_aliased_tables))
                {
                    $part = $this->_protectIdentifiers(trim($part));
                }
                
                $temp[] = $part;
            }
            
            $orderby = implode(', ', $temp);            
        }
        else
        {
            $orderby = $this->_protectIdentifiers($orderby);
        }
    
        $orderby_statement  = $orderby.$direction;
        $this->ar_orderby[] = $orderby_statement;
        
        if ($this->ar_caching === true)
        {
            $this->ar_cache_orderby[] = $orderby_statement;
            $this->ar_cache_exists[]  = 'orderby';
        }
        
        return $this;

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
    public function set($key, $value = '', $escape = true)
    {
        $key = $this->_object2Array($key);
        
        if ( ! is_array($key))
        {
            $key = array($key => $value);
        }

        foreach ($key as $k => $v)
        {
            if ($escape === false)                      
            {                                           
                // obullo changes.. 
                // make sure is it bind value, if not ... 
                if( strpos($v, ':') === false || strpos($v, ':') > 0)
                {
                     if(is_string($v))
                     {
                         $v = "'{$v}'";  // obullo changes..
                     }

                }
            
                // obullo changes..
                $this->ar_set[$this->_protectIdentifiers($k)] = $v;
            }
            else
            {
                $this->ar_set[$this->_protectIdentifiers($k)] = $this->escape($v);
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
            $this->_trackAliases($table);
            $this->from($table);
        }
        
        if ( ! is_null($limit))
        {
            $this->limit($limit, $offset);
        }
            
        $this->sql = $this->_compileSelect();
        
        if($this->prepare == false)    // obullo changes ..
        {
            $result = $this->query($this->sql);
            $this->_resetSelect();
            
            return $result;
        
        } elseif($this->prepare) // passive mode...
        {
            $this->query($this->sql);  
            $this->_resetSelect();
            
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
    public function insert($table = '', $set = null)
    {    
        if ( ! is_null($set))
        {
            $this->set($set);
        }
        
        if (count($this->ar_set) == 0)
        {
            throw new \Exception('Please set values for insert operation.');
            
            return false;
        }

        if ($table == '')
        {
            if ( ! isset($this->ar_from[0]))
            {
                throw new \Exception('Please set values for insert operation.');
                
                return false;
            }
            
            $table = $this->ar_from[0];
        }

        $sql = $this->_insert($this->_protectIdentifiers($table, true, null, false), array_keys($this->ar_set), array_values($this->ar_set));
        
        $this->_resetWrite();
        
        $this->prepare = false;
        
        return $this->execQuery($sql);  // return affected rows ( PDO support )
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
    public function replace($table = '', $set = null)
    {
        if ( ! is_null($set))
        {
            $this->set($set);
        }

        if (count($this->ar_set) == 0)
        {
            throw new \Exception('Please set values for replace operation.');
        }

        if ($table == '')
        {
            if ( ! isset($this->ar_from[0]))
            {
                throw new \Exception('Please set from for replace operation.');
            }

            $table = $this->ar_from[0];
        }

        $sql = $this->_replace($this->_protectIdentifiers($table, true, null, false), array_keys($this->ar_set), array_values($this->ar_set));

        $this->_resetWrite();
        
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
    public function insertString($table, $data)
    {
        $fields = array();
        $values = array();
        
        foreach($data as $key => $val)
        {
            $fields[] = $this->_escapeIdentifiers($key);
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
    public function update($table = '', $set = null, $where = null, $limit = null)
    {
        // Combine any cached components with the current statements
        $this->_mergeCache();
        
        if ( ! is_null($set))
        {
            $this->set($set);
        }
    
        if (count($this->ar_set) == 0)
        {
            throw new \Exception('Please set values for update operation.');
            
            return false;
        }
                                         
        if ($table == '')
        {
            if ( ! isset($this->ar_from[0]))
            {
                throw new \Exception('Please set values for update operation.'); 
                
                return false;
            }
            
            $table = $this->ar_from[0];
        }
        
        if ($where != null)
        {
            $this->where($where);
        }
        
        if ($limit != null)
        {
            $this->limit($limit);   
        }       
        
        $sql = $this->_update($this->_protectIdentifiers($table, true, null, false), $this->ar_set, $this->ar_where, $this->ar_orderby, $this->ar_limit);
                 
        $this->_resetWrite();
        
        $this->prepare = false;
        
        return $this->execQuery($sql);  // return number of affected rows.  
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
    public function updateString($table, $data, $where)
    {
        if ($where == '')
        {
            return false;
        }
                    
        $fields = array();
        foreach($data as $key => $val)
        {
            $fields[$this->_protectIdentifiers($key)] = $this->escape($val);
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
                    if ( ! self::_hasOperator($key))
                    {
                        $key .= ' =';
                    }
                
                    $val = ' '.$this->escape($val);
                }
                            
                $dest[] = $prefix.$key.$val;
            }
        }        

        return $this->_update($this->_protectIdentifiers($table, true, null, false), $fields, $dest);
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
    public function delete($table = '', $where = '', $limit = null, $reset_data = true)
    {
        // Combine any cached components with the current statements
        $this->_mergeCache();

        if ($table == '')
        {
            if ( ! isset($this->ar_from[0]))
            {
                throw new \Exception('Please set table for delete operation.');
                
                return false;
            }

            $table = $this->ar_from[0];
        }
        elseif (is_array($table))
        {
            foreach($table as $single_table)
            {
                $this->delete($single_table, $where, $limit, false);   
            }
        
            $this->_resetWrite();
            return;
        } else 
        {
            $table = $this->_protectIdentifiers($table, true, null, false);
        }

        if ($where != '')
        {
            $this->where($where);
        }

        if ($limit != null)
        {
            $this->limit($limit);
        }
        
        if (count($this->ar_where) == 0 && count($this->ar_wherein) == 0 && count($this->ar_like) == 0)
        {
            throw new \Exception("Deletes are not allowed unless they contain a 'where' or 'like' clause.");
            
            return false;
        }        

        $sql = $this->_delete($table, $this->ar_where, $this->ar_like, $this->ar_limit);
        
        if ($reset_data)
        {
            $this->_resetWrite();
        }
        
        $this->prepare = false;
        
        return $this->execQuery($sql); // return number of  affected rows
    
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
    private function _trackAliases($table)
    {
        if (is_array($table))
        {
            foreach ($table as $t)
            {
                $this->_trackAliases($t);
            }
            return;
        }
        
        // Does the string contain a comma?  If so, we need to separate
        // the string into discreet statements
        if (strpos($table, ',') !== false)
        {
            return $this->_trackAliases(explode(',', $table));
        }
    
        // if a table alias is used we can recognize it by a space
        if (strpos($table, " ") !== false)
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
    private function _compileSelect($select_override = false)
    {
        // Combine any cached components with the current statements
        $this->_mergeCache();

        // ----------------------------------------------------------------
        
        // Write the "select" portion of the query

        if ($select_override !== false)
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
                    $this->ar_select[$key] = $this->_protectIdentifiers($val);
                }
                
                $sql .= implode(', ', $this->ar_select);
            }
        }

        // ----------------------------------------------------------------
        
        // Write the "FROM" portion of the query

        if (count($this->ar_from) > 0)
        {
            $sql .= "\nFROM ";

            $sql .= $this->_fromTables($this->ar_from);
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
            
            if ($this->ar_order !== false)
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
    public function _object2Array($object)
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
    
    // --------------------------------------------------------------------
   
    /**
    * Start Cache
    *
    * Starts AR caching
    *
    * @access    public
    * @return    void
    */        
    public function startCache()
    {
        $this->ar_caching = true;
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
    public function stopCache()
    {
        $this->ar_caching = false;
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
    public function flushCache()
    {    
        $this->_resetRun(
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
    private function _mergeCache()
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
    private function _resetRun($ar_reset_items)
    {
        foreach ($ar_reset_items as $item => $default_value)
        {
            if ( ! in_array($item, $this->ar_store_array))
            {
                $this->$item = $default_value;
            }

        }
    }
 
   // --------------------------------------------------------------------

    /**
    * Resets the active record values.  Called by the get() function
    *
    * @access    private
    * @return    void
    */
    public function _resetSelect()
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
                                'ar_distinct'       => false, 
                                'ar_limit'          => false, 
                                'ar_offset'         => false, 
                                'ar_order'          => false,
                            );
        
        $this->_resetRun($ar_reset_items);
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
    private function _resetWrite()
    {    
        $ar_reset_items = array(
                                'ar_set'        => array(), 
                                'ar_from'       => array(), 
                                'ar_where'      => array(), 
                                'ar_like'       => array(),
                                'ar_orderby'    => array(), 
                                'ar_limit'      => false, 
                                'ar_order'      => false
                                );

        $this->_resetRun($ar_reset_items);
    }

    // --------------------------------------------------------------------

    /**
    * Tests whether the string has an SQL operator
    *
    * @access   private
    * @param    string
    * @return   bool
    */ 
    private static function _hasOperator($str)
    {
        $str = trim($str);
        if ( ! preg_match("/(\s|<|>|!|=|is null|is not null)/i", $str))
        {
            return false;
        }

        return true;
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
    private function protectIdentifiers($item, $prefix_single = false)
    {
        return $this->_protectIdentifiers($item, $prefix_single);
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
    public function _protectIdentifiers($item, $prefix_single = false, $protect_identifiers = null, $field_exists = true)
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
                $escaped_array[$this->_protectIdentifiers($k)] = $this->_protectIdentifiers($v);
            }

            return $escaped_array;
        }

        // Convert tabs or multiple spaces into single spaces
        $item = preg_replace('/[\t ]+/', ' ', $item);

        // If the item has an alias declaration we remove it and set it aside.
        // Basically we remove everything to the right of the first space
        $alias = '';
        if (strpos($item, ' ') !== false)
        {
            $alias = strstr($item, " ");
            $item  = substr($item, 0, - strlen($alias));
        }

        // This is basically a bug fix for queries that use MAX, MIN, etc.
        // If a parenthesis is found we know that we do not need to
        // escape the data or add a prefix.  There's probably a more graceful
        // way to deal with this, but I'm not thinking of it -- Rick
        if (strpos($item, '(') !== false)
        {
            return $item.$alias;
        }

        // Break the string apart if it contains periods, then insert the table prefix
        // in the correct location, assuming the period doesn't indicate that we're dealing
        // with an alias. While we're at it, we will escape the components
        if (strpos($item, '.') !== false)
        {
            $parts = explode('.', $item);

            // Does the first segment of the exploded item match
            // one of the aliases previously identified?  If so,
            // we have nothing more to do other than escape the item
            if (in_array($parts[0], $this->ar_aliased_tables))
            {
                if ($protect_identifiers === true)
                {
                    foreach ($parts as $key => $val)
                    {
                        if ( ! in_array($val, $this->_reserved_identifiers))
                        {
                            $parts[$key] = $this->_escapeIdentifiers($val);
                        }
                    }

                    $item = implode('.', $parts);
                }
                return $item.$alias;
            }

            // Is there a table prefix defined in the config file?  If not, no need to do anything
            if ($this->prefix != '')
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
                if ($field_exists == false)
                {
                    $i++;
                }

                // Verify table prefix and replace if necessary
                if ($this->swap_pre != '' && strncmp($parts[$i], $this->swap_pre, strlen($this->swap_pre)) === 0)
                {
                    $parts[$i] = preg_replace("/^".$this->swap_pre."(\S+?)/", $this->prefix."\\1", $parts[$i]);
                }

                // We only add the table prefix if it does not already exist
                if (substr($parts[$i], 0, strlen($this->prefix)) != $this->prefix)
                {
                    $parts[$i] = $this->prefix.$parts[$i];
                }

                // Put the parts back together
                $item = implode('.', $parts);
            }

            if ($protect_identifiers === true)
            {
                $item = $this->_escapeIdentifiers($item);
            }

            return $item.$alias;
        }

        // Is there a table prefix?  If not, no need to insert it
        if ($this->prefix != '')
        {
            // Verify table prefix and replace if necessary
            if ($this->swap_pre != '' && strncmp($item, $this->swap_pre, strlen($this->swap_pre)) === 0)
            {
                $item = preg_replace("/^".$this->swap_pre."(\S+?)/", $this->prefix."\\1", $item);
            }

            // Do we prefix an item with no segments?
            if ($prefix_single == true AND substr($item, 0, strlen($this->prefix)) != $this->prefix)
            {
                $item = $this->prefix.$item;
            }
        }

        if ($protect_identifiers === true AND ! in_array($item, $this->_reserved_identifiers))
        {
            $item = $this->_escapeIdentifiers($item);
        }

        return $item.$alias;
    }
                 
                                  
}

/* End of file database_crud.php */
/* Location: ./ob/database_pdo/releases/0.0.1/src/database_crud.php */