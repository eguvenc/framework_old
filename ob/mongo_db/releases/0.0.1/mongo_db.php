<?php

/**
 * Mongo Db Class.
 *
 * A library to interface for NoSQL database Mongo. For more information see http://www.mongodb.org
 *
 * @package		Obullo
 * @author		Alex Bilbie | www.alexbilbie.com | alex@alexbilbie.com ( Original Library )
 * @author		Ersin Güvenç. ( Porting to Obullo 2.0 )
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		http://alexbilbie.com
 * @version		Version 0.0.1
 *
 */

Class Mongo_Db {

    private $db;
    public  $connection = null;    // ! do not remove we close the connection in the bootstrap.
    private $connection_string;

    private $host;
    private $port;
    private $user;
    private $pass;
    private $dbname;
    private $query_safety = array();

    private $selects  = array();
    public  $wheres   = array(); // Public to make debugging easier
    private $sorts    = array();
    public  $updates  = array(); // Public to make debugging easier

    private $limit = 999999;
    private $offset = 0;
    private $last_inserted_id = ''; // Last inserted id.
    
    private $collection = ''; // Set collection name using $this->db->from() ?
    public $use_mongo_id = true; // Use or not use mongoid object

    /**
    * Constructor
    * 
    * Automatically check if the Mongo PECL extension has been installed/enabled.
    * Generate the connection string and establish a connection to the MongoDB.
    * 
    * @throws Exception 
    */
    public function __construct($no_instance = true)
    {
        if ( ! class_exists('Mongo'))
        {
            throw new Exception('The MongoDB PECL extension has not been installed or enabled.');
        }
        
        $this->setConnectionString(); // Build the connection string from the config file
        $this->connect();
        
        if($no_instance)
        {
            getInstance()->mongo = $this; // Available it in the contoller $this->mongo->method();
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Initialize to Class.
     * 
     * @return object
     */
    public function init()
    {
        return ($this);
    }
    
    // --------------------------------------------------------------------
    
    public function useMongoid($bool = true)
    {
        $this->use_mongo_id = $bool;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Determine which fields to include OR which to exclude during the query process.
     * Currently, including and excluding at the same time is not available, so the
     * $includes array will take precedence over the $excludes array.
     * 
     * If you want to only choose fields to exclude, leave $includes an empty array().
     * 
     * @usage: $this->db->select('foo,bar'))->get('foobar');
     * 
     * @param type $includes
     * @param array $excludes
     * @return type 
     */
    public function select($includes = '', $excludes = array())
    {
        if(is_string($includes) AND strpos($includes, ',') > 0)
        {
            $includes = explode(',', $includes);
            $includes = array_map('trim', $includes);
        }
        
        if ( ! is_array($includes))
        {
            $includes = array();
        }

        if ( ! is_array($excludes))
        {
            $excludes = array();
        }

        if ( ! empty($includes))
        {
            foreach ($includes as $col)
            {
                $this->selects[$col] = 1;
            }
        }
        else
        {
            foreach ($excludes as $col)
            {
                $this->selects[$col] = 0;
            }
        }

        return ($this);
    }

    // --------------------------------------------------------------------
    
    /**
     * Set a collection.
     * 
     * @param type $collection 
     * @return \Db
     */
    public function from($collection = '')
    {
        $this->collection = $collection;
        
        return ($this);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get the documents based on these search parameters. The $wheres array can
     * be an associative array with the field as the key and the value as the search
     * criteria.
     * 
     * @usage : $this->db->where('foo','bar'))->get('foobar');
     * @usage : $this->db->where('foo >', 20)->get('foobar');
     * @usage : $this->db->where('foo <', 20)->get('foobar');
     * @usage : $this->db->where('foo >=', 20)->get('foobar');
     * @usage : $this->db->where('foo <=', 20)->get('foobar');
     * @usage : $this->db->where('foo !=', 20)->get('foobar');
     * 
     * @usage : $this->db->where('foo <', 10)->where('foo >', 25)->get('foobar');
     * @usage : $this->db->where('foo <=', 10)->where('foo >=', 25)->get('foobar');
     * 
     * @param type $wheres
     * @param type $value
     * @return \Db
     */
    public function where($wheres, $value = null, $mongo_id = true)
    {
        if(is_string($wheres) AND strpos(ltrim($wheres), ' ') > 0)
        {
            $array    = explode(' ', $wheres);
            $field    = $array[0];
            $criteria = $array[1];
            
            $this->_whereInit($field);
            
            switch ($criteria)
            {
                case '>':    // greater than
                    $this->wheres[$field]['$gt']  = $value;
                    break;
                
                case '<':    // less than
                    $this->wheres[$field]['$lt']  = $value;
                    break;
                
                case '>=':   // greater than or equal to
                    $this->wheres[$field]['$gte'] = $value;
                    break;
                
                case '<=':   // less than or equal to
                    $this->wheres[$field]['$lte'] = $value;
                    break;
                
                case '!=':   // not equal to
                    $this->wheres[$field]['$ne']  = $this->_isMongoId($field, $value);
                    break;
                
                default:
                    break;
            }
            
            return ($this);
        }
        
        if (is_array($wheres))
        {
            foreach ($wheres as $wh => $val)
            {
                $this->wheres[$wh] = $this->_isMongoId($wh, $val);
            }
        }
        else
        {
            $this->wheres[$wheres] = $this->_isMongoId($wheres, $value);   
        }

        return ($this);
    }

    // --------------------------------------------------------------------

    /**
     * Get the documents where the value of a $field may be something else
     * 
     * @usage : $this->db->orWhere('foo','bar')->get('foobar');
     * 
     * @param type $wheres
     * @return Db
     */
    public function orWhere($wheres, $value = null)
    {
        if (is_array($value))
        {
            foreach ($value as $wh => $val)
            {
                $this->wheres['$or'][][$wh] = $this->_isMongoId($wh, $val);
            }
        }
        else
        {
            $this->wheres['$or'][][$wheres] = $this->_isMongoId($wheres, $value);
        }
        
        return ($this);
    }

    // --------------------------------------------------------------------

    /**
     * Get the documents where the value of a $field is in a given $in array().
     * 
     * @usage : $this->db->whereIn('foo', array('bar', 'zoo', 'blah'))->get('foobar');
     * @usage : $this->db->whereIn('foo !=', array('bar', 'zoo', 'blah'))->get('foobar');
     * 
     * @param type $field
     * @param type $in
     * @return \Db
     */
    public function whereIn($field = "", $in = array())
    {
        if(strpos($field, '!=') > 0)
        {
            $array = explode('!=', $field);
            $field = trim($array[0]);
            
            $this->_whereInit($field);
            $this->wheres[$field]['$nin'] = $in;
            
            return ($this);
        }
        
        $this->_whereInit($field);
        $this->wheres[$field]['$in'] = $in;
        
        return ($this);
    }

    // --------------------------------------------------------------------
    
    /**
     * Get the documents where the value of a $field is in all of a given $in array().
     * 
     * @usage : $this->db->whereInAll('foo', array('bar', 'zoo', 'blah'))->get('foobar');
     * 
     * @param type $field
     * @param type $in
     * @return \Db
     */
    public function whereInAll($field = "", $in = array())
    {
        $this->_whereInit($field);
        $this->wheres[$field]['$all'] = $in;
        
        return ($this);
    }
    
    // --------------------------------------------------------------------
    
     /**
     *
     * 	Get the documents where the (string) value of a $field is like a value. The defaults
     * 	allow for a case-insensitive search.
     *
     * 	@param $flags
     * 	Allows for the typical regular expression flags:
     * 		i = case insensitive
     * 		m = multiline
     * 		x = can contain comments
     * 		l = locale
     * 		s = dotall, "." matches everything, including newlines
     * 		u = match unicode
     *
     * 	@param $enable_start_wildcard
     * 	If set to anything other than true, a starting line character "^" will be prepended
     * 	to the search value, representing only searching for a value at the start of
     * 	a new line.
     *
     * 	@param $enable_end_wildcard
     * 	If set to anything other than true, an ending line character "$" will be appended
     * 	to the search value, representing only searching for a value at the end of
     * 	a line.
     *
     * 	@usage : $this->db->like('foo', 'bar', 'im', false, true);
     * 	@since v1.0.0
     *
     */
    public function like($field = "", $value = "", $flags = "i", $enable_start_wildcard = true, $enable_end_wildcard = true)
    {
        $field = (string) trim($field);
        $this->_whereInit($field);
        $value = (string) trim($value);
        $value = quotemeta($value);

        if ($enable_start_wildcard !== true)
        {
            $value = "^" . $value;
        }
        
        if ($enable_end_wildcard !== true)
        {
            $value .= "$";
        }
        
        $regex = "/$value/$flags";
        $this->wheres[$field] = new MongoRegex($regex);
        
        return $this;
    }

    // --------------------------------------------------------------------
    
    /**
     * The same as the aboce but multiple instances are joined by OR:
     *
     * @since v1.0.0
     */
    public function orLike($field, $like = array())
    {
        $this->_whereInit('$or');
        if (is_array($like) && count($like) > 0)
        {
            foreach ($like as $admitted)
            {
                $this->wheres['$or'][] = array($field => new MongoRegex("/$admitted/"));
            }
        }
        return $this;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * The same as the above but multiple instances are joined by NOT LIKE:
     *
     * @since v1.0.0
     */
    public function notLike($field, $like = array())
    {
        $this->_whereInit($field);
        if (is_array($like) && count($like) > 0)
        {
            foreach ($like as $admitted)
            {
                $this->wheres[$field]['$nin'][] = new MongoRegex("/$admitted/");
            }
        }
        return $this;
    }

     // --------------------------------------------------------------------
    
    /**
     * @usage : $this->db->orderBy('foo', 'ASC'))->get('foobar');
     * 
     * @param type $fields
     * @return \Db
     */
    public function orderBy($col, $direction  = 'ASC')
    {
        if (strtolower($direction) == 'desc')
        {
            $this->sorts[$col] = -1;
        }
        else
        {
            $this->sorts[$col] = 1;
        }

        return ($this);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Limit the result set to $x number of documents.
     * 
     * @usage : $this->db->limit($x);
     * 
     * @param type $x
     * @return \Db
     */
    public function limit($x = 99999)
    {
        if ($x !== null && is_numeric($x) && $x >= 1)
        {
            $this->limit = (int) $x;
        }
        
        return ($this);
    }

    // --------------------------------------------------------------------
   
    /**
     * Offset the result set to skip $x number of documents.
     * 
     * @usage : $this->db->offset($x);
     * 
     * @param type $x
     * @return \Db
     */
    public function offset($x = 0)
    {
        if ($x !== null && is_numeric($x) && $x >= 1)
        {
            $this->offset = (int) $x;
        }
        
        return ($this);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * @usage : $this->select()->from('collection')->find($criteria);
     * 
     * @param type $criteria
     * @param type $fields
     * @return Mongo::Cursor Object
     * @throws Exception 
     */
    public function find($criteria = array(), $fields = array())
    {
        if($this->collection == '')
        {
            throw new Exception('You need to set a collection name using $this->db->from(\'table\') method.');
        }
        
        $re_criteria = array();
        foreach ($criteria as $key => $value)
        {
            $re_criteria[$key] = $this->_isMongoId($key, $value);
        }
        
        $docs = $this->db->{$this->collection}->find($re_criteria, array_merge($this->selects, $fields))
                ->limit((int) $this->limit)->skip((int) $this->offset)->sort($this->sorts);
        
        $this->_resetSelect();         // Reset
        
        return $docs;
    }
    
    // --------------------------------------------------------------------
    
     /**
     * @usage : $this->select()->from('collection')->findOne($criteria);
     * 
     * @param type $criteria
     * @param type $fields
     * @return Mongo::Cursor Object
     * @throws Exception 
     */
    public function findOne($criteria = array(), $fields = array())
    {
        if($this->collection == '')
        {
            throw new Exception('You need to set a collection name using $this->db->from(\'table\') method.');
        }

        $re_criteria = array();
        foreach ($criteria as $key => $value)
        {
            $re_criteria[$key] = $this->_isMongoId($key, $value);
        }
        
        $docs = $this->db->{$this->collection}->findOne($re_criteria, array_merge($this->selects, $fields));
        
        $this->_resetSelect();         // Reset
        
        return $docs;
    }
    
    // --------------------------------------------------------------------
   
    /**
     * Get the documents based upon the passed parameters.
     * 
     * @usage : $this->db->get('foo');
     * 
     * @param type $collection
     * @return Mongo::Cursor Object
     * @throws Exception 
     */
    public function get($collection = '')
    {
        $collection = (empty($this->collection)) ? $collection : $this->collection;
        
        if (empty($collection))
        {
            throw new Exception('You need to set a collection name using $this->db->from(\'table\') method.');
        }
        
        $docs = $this->db->{$collection}->find($this->wheres, $this->selects)
            ->limit((int) $this->limit)->skip((int) $this->offset)->sort($this->sorts);
        
        $this->_resetSelect();         // Reset
        
        return $docs;
    }

    // --------------------------------------------------------------------
        
    /**
     *  Perform an aggregation using the aggregation framework
     *  @link http://docs.mongodb.org/manual/aggregation/
     *  @link http://docs.mongodb.org/manual/reference/sql-aggregation-comparison/
     *  WHERE	  $match
        GROUP BY  $group
        HAVING 	  $match
        SELECT	  $project
        ORDER BY  $sort
        LIMIT	  $limit
        SUM()	  $sum
        COUNT()	  $sum
        join	  No direct corresponding operator; however, the $unwind operator allows for 
     *  somewhat similar functionality, but with fields embedded within the document.
     * 
     * @param array $pipeline
     * @param array $options
     * @return array results
     * @throws Exception
     */
    public function aggregate($pipeline, $options = null)
    {
        $collection = (empty($this->collection)) ? $collection : $this->collection;
        
        if (empty($collection))
        {
            throw new Exception('You need to set a collection name using $this->db->from(\'table\') method.');
        }
        
        if(is_array($options))
        {
            $docs = $this->db->{$collection}->aggregate($pipeline, $options);
        } 
        else 
        {
            $docs = $this->db->{$collection}->aggregate($pipeline);
        }

        $this->collection = ''; // reset from.
        
        return $docs;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Insert a new document into the passed collection
     *
     * @usage : $this->db->insert('foo', $data = array());
     * 
     * @param string $collection
     * @param array $insert
     * @return int affected rows
     * @throws Exception 
     */
    public function insert($collection = "", $insert = array(), $options = array())
    {
        if (empty($collection))
        {
            throw new Exception('No Mongo collection selected to insert into.');
        }

        if (count($insert) == 0 || ! is_array($insert))
        {
            throw new Exception('Nothing to insert into Mongo collection or insert is not an array.');
        }

        try
        {
            $this->db->{$collection}->insert($insert, array_merge($this->query_safety, $options));
            
            if (isset($insert['_id']))
            {
                $this->last_inserted_id = $insert['_id'];
                
                return count(array_pop($insert)); // affected rows.
            }
            else
            {
                return (false);
            }
        }
        catch (MongoCursorException $e)
        {
            throw new Exception('Insert of data into MongoDB failed: '.$e->getMessage());
        }
    }

    // --------------------------------------------------------------------
    
    /**
     * Batch Insert
     * 
     * Insert a multiple new document into the passed collection.
     * 
     * @usage : $this->db->batchInsert('foo', $data = array());
     * 
     * @param type $collection
     * @param type $insert
     * @return type
     * @throws Exception 
     */
    public function batchInsert($collection = "", $insert = array(), $options = array())
    {
        if (empty($collection))
        {
            throw new Exception('No Mongo collection selected to insert operation.');
        }

        if (count($insert) == 0 || ! is_array($insert))
        {
            throw new Exception('Nothing to insert into Mongo collection or insert is not an array.');
        }

        try
        {
            $this->db->{$collection}->batchInsert($insert, array_merge($this->query_safety, $options));
            
            if (isset($insert['_id']))
            {
                return ($insert['_id']);
            }
            else
            {
                return (false);
            }
        }
        catch (MongoCursorException $e)
        {
            throw new Exception('Insert of data into MongoDB failed: '.$e->getMessage());
        }
    }

    // --------------------------------------------------------------------
    
    /**
     * Updates multiple document
     * 
     * @usage: $this->db->update('foo', $data = array());
     * 
     * @param string $collection
     * @param array $data
     * @param array $options
     * @return int affected rows
     * @throws Exception 
     */
    public function update($collection = "", $data = array(), $options = array())
    {
        if (empty($collection))
        {
            throw new Exception('No Mongo collection selected to update.');
        }

        if (is_array($data) AND count($data) > 0)
        {
            $this->updates = array_merge($data, $this->updates);
        }

        if (count($this->updates) == 0)
        {
            throw new Exception('Nothing to update in Mongo collection or update is not an array.');
        }

        // Update Modifiers  http://www.mongodb.org/display/DOCS/Updating
        $mods = array('$set' => '', '$unset' => '', '$pop' => '', '$push' => '', '$pushAll' => '','$pull' => '', 
            '$pullAll' => '', '$inc' => '', '$each' => '', '$addToSet' => '', '$rename' => '', '$bit' => '');
        
        // Multiple update behavior like MYSQL.
        $default_options = array_merge(array('multiple' => true), $this->query_safety);
        
        ##  If any modifier used remove the default modifier ( $set ).
        $used_modifier = array_keys($this->updates);
        $modifier      = (isset($used_modifier[0])) ? $used_modifier[0] : null;
        
        if($modifier != null AND isset($mods[$modifier]))
        {
            $updates = $this->updates;
            $default_options['multiple'] = false;
        }
        else 
        {
            $updates = array('$set' => $this->updates); // default mod = $set
        }
        
        try
        {
            $this->db->{$collection}->update($this->wheres, $updates, array_merge($default_options, $options));
            $this->_resetSelect();
            return $this->db->{$collection}->find($updates)->count();
        }
        catch (MongoCursorException $e)
        {
            throw new Exception('Update of data into MongoDB failed: '.$e->getMessage());
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Increments the value of a field.
     * 
     * @usage: $this->db->where(blog_id, 123)->inc('num_comments', 1))->update('blog_posts');
     * 
     * @param type $fields
     * @param type $value
     * @return \Db
     */
    public function inc($fields = '', $value = 0)
    {
        $this->_updateInit('$inc');

        if (is_string($fields))
        {
            $this->updates['$inc'][$fields] = $value;
        }
        elseif (is_array($fields))
        {
            foreach ($fields as $field => $value)
            {
                $this->updates['$inc'][$field] = $value;
            }
        }

        return ($this);
    }

    // --------------------------------------------------------------------
    
    /**
     * Decrements the value of a field.
     * 
     * @usage: $this->db->where('blog_id', 123))->dec('num_comments', 1))->update('blog_posts');
     * 
     * @param type $fields
     * @param type $value
     * @return \Db
     */
    public function dec($fields = '', $value = 0)
    {
        $this->_updateInit('$inc');

        if (is_string($fields))
        {
            $this->updates['$inc'][$fields] = $value;
        }
        elseif (is_array($fields))
        {
            foreach ($fields as $field => $value)
            {
                $this->updates['$inc'][$field] = $value;
            }
        }

        return ($this);
    }

    // --------------------------------------------------------------------
    
    /**
     * Sets a field to a value.
     * 
     * @usage: $this->db->where('blog_id',123)->set('posted', 1)->update('blog_posts');
     * @usage: $this->db->where('blog_id',123)->set(array('posted' => 1, 'time' => time()))->update('blog_posts');
     * 
     * @param type $fields
     * @param type $value
     * @return \Db
     */
    public function set($fields, $value = null)
    {
        $this->_updateInit('$set');

        if (is_string($fields))
        {
            $this->updates['$set'][$fields] = $value;
        }
        elseif (is_array($fields))
        {
            foreach ($fields as $field => $value)
            {
                $this->updates['$set'][$field] = $value;
            }
        }

        return ($this);
    }

    // --------------------------------------------------------------------
    
    /**
     * Unsets a field (or fields).
     * 
     * @usage: $this->db->where('blog_id', 123)->unset('posted')->update('blog_posts');
     * @usage: $this->db->where('blog_id', 123)->set('posted','time')->update('blog_posts');
     * 
     * @param type $fields
     * @return \Db
     */
    public function unsetField($fields)
    {
        $this->_updateInit('$unset');

        if (is_string($fields))
        {
            $this->updates['$unset'][$fields] = 1;
        }
        elseif (is_array($fields))
        {
            foreach ($fields as $field)
            {
                $this->updates['$unset'][$field] = 1;
            }
        }

        return ($this);
    }

    // --------------------------------------------------------------------
    
    /**
     * Adds value to the array only if its not in the array already.
     * 
     * @usage: $this->db->where('blog_id', 123))->add2set('tags', 'php')->update('blog_posts');
     * @usage: $this->db->where('blog_id', 123))->add2set('tags', array('php', 'obullo', 'mongodb'))->update('blog_posts');
     * 
     * @param type $field
     * @param type $values
     * @return \Db
     */
    public function add2set($field, $values)
    {
        $this->_updateInit('$addToSet');

        if (is_string($values))
        {
            $this->updates['$addToSet'][$field] = $values;
        }
        elseif (is_array($values))
        {
            $this->updates['$addToSet'][$field] = array('$each' => $values);
        }

        return ($this);
    }

    // --------------------------------------------------------------------

    /**
     * Pushes values into a field (field must be an array).
     * 
     * @usage: $this->db->where('blog_id', 123)->push('comments', array('text'=>'Hello world'))->update('blog_posts');
     * @usage: $this->db->where('blog_id', 123)->push(array('comments' => array('text'=>'Hello world')), 'viewed_by' => array('Alex'))->update('blog_posts');
     * 
     * @param type $fields
     * @param type $value
     * @return \Db
     */
    public function push($fields, $value = array())
    {
        $this->_updateInit('$push');

        if (is_string($fields))
        {
            $this->updates['$push'][$fields] = $value;
        }

        elseif (is_array($fields))
        {
            foreach ($fields as $field => $value)
            {
                $this->updates['$push'][$field] = $value;
            }
        }

        return ($this);
    }
    
    // --------------------------------------------------------------------

    /**
     * Pops the last value from a field (field must be an array).
     *  
     * @usage: $this->db->where('blog_id', 123))->pop('comments')->update('blog_posts');
     * @usage: $this->db->where('blog_id', 123))->pop(array('comments', 'viewed_by'))->update('blog_posts');
     * 
     * @param type $field
     * @return \Db
     */
    public function pop($field)
    {
        $this->_updateInit('$pop');

        if (is_string($field))
        {
            $this->updates['$pop'][$field] = -1;
        }

        elseif (is_array($field))
        {
            foreach ($field as $pop_field)
            {
                $this->updates['$pop'][$pop_field] = -1;
            }
        }

        return ($this);
    }
    
    // --------------------------------------------------------------------

    /**
     * Removes by an array by the value of a field.
     * 
     * @usage: $this->db->pull('comments', array('comment_id'=>123))->update('blog_posts');
     * 
     * @param type $field
     * @param type $value
     * @return \Db
     */
    public function pull($field = "", $value = array())
    {
        $this->_updateInit('$pull');

        $this->updates['$pull'] = array($field => $value);

        return ($this);
    }

    // --------------------------------------------------------------------
    
    /**
     * Delete all documents from the passed collection based upon certain criteria.
     * 
     * @usage : $this->db->delete('foo', $data = array());
     * 
     * @param string $collection
     * @return int affected rows.
     * @throws Exception 
     */
    public function delete($collection = '', $options = array())
    {
        $default_options = array_merge(array('justOne' => false), $this->query_safety);

        if (empty($collection))
        {
            throw new Exception('No Mongo collection selected to delete.');
        }

        if (isset($this->wheres['_id']) AND ! ($this->wheres['_id'] instanceof MongoId))
        {
            $this->wheres['_id'] = new MongoId($this->wheres['_id']);
        }

        try
        {
            $affected_rows = $this->db->{$collection}->find($this->wheres)->count();
            
            $this->db->{$collection}->remove($this->wheres, array_merge($default_options, $options));
            
            $this->_resetSelect();
            
            return $affected_rows;
        }
        catch (MongoCursorException $e)
        {
            throw new Exception('MongoDB data delete operation failed: '.$e->getMessage());
        }
    }

    // --------------------------------------------------------------------
    
    /**
     * Establish a connection to MongoDB using the connection string generated in
     * the setConnectionString() method.  If 'mongo_persist_key' was set to true in the
     * config file, establish a persistent connection.
     * 
     * We allow for only the 'persist'
     * option to be set because we want to establish a connection immediately.
     * 
     * @return type
     * @throws Exception 
     */
    public function connect()
    {
        if($this->db == null)
        {
            try
            {
                $this->connection = new Mongo($this->connection_string);
                $this->db         = $this->connection->{$this->dbname};

                return ($this);	
            } 
            catch (MongoConnectionException $e)
            {
                throw new Exception('Unable to connect to MongoDB: '.$e->getMessage());
            }
        }
    }

    // --------------------------------------------------------------------
    
    /**
     * Returns to Mongodb instance of object.
     * 
     * @return object
     */
    public function getInstance()
    {
        return $this->db;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Build the connection string from the config file.
     * 
     * @throws Exception 
     */
    private function setConnectionString() 
    {
        $config = getConfig('mongo');
        
        if($config['dsn'] != '')
        {
            $this->connection_string = $config['dsn'];
            return;
        }
        
        $this->host         = $config['host'];
        $this->port         = $config['port'];
        $this->user         = $config['username'];
        $this->pass         = $config['password'];
        $this->dbname       = $config['database'];
        $this->query_safety = $config['query_safety'];
        
        if($this->dbname == '')
        {
            throw new Exception('Please set a $mongo[\'database\'] from app/config/mongo.php.');
        }
        
        $dbname_flag = (bool)$config['dbname_flag'];
        $connection_string = "mongodb://";

        if (empty($this->host))
        {
            throw new Exception('You need to specify a hostname connect to MongoDB.');
        }

        if (empty($this->dbname))
        {
            throw new Exception('You need to specify a database name connect to MongoDB.');
        }

        if ( ! empty($this->user) AND ! empty($this->pass))
        {
            $connection_string .= "{$this->user}:{$this->pass}@";
        }

        if (isset($this->port) AND ! empty($this->port))
        {
            $connection_string .= "{$this->host}:{$this->port}";
        }
        else
        {
            $connection_string .= "{$this->host}";
        }

        if ($dbname_flag === true)
        {
            $this->connection_string = trim($connection_string) . '/' . $this->dbname;
        }
        else
        {
            $this->connection_string = trim($connection_string);
        }
    }

    // --------------------------------------------------------------------

    /**
     *  Resets the class variables to default settings
     */
    public function _resetSelect()
    {
        $this->selects	= array();
        $this->updates	= array();
        $this->wheres	= array();
        $this->limit	= 999999;
        $this->offset	= 0;
        $this->sorts	= array();
        $this->find     = false;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Prepares parameters for insertion in $wheres array().
     * 
     * @param type $param 
     */
    private function _whereInit($param)
    {
        if ( ! isset($this->wheres[$param]))
        {
            $this->wheres[ $param ] = array();
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Prepares parameters for insertion in $updates array().
     * 
     * @param type $method 
     */
    private function _updateInit($method)
    {
        if ( ! isset($this->updates[$method]))
        {
            $this->updates[ $method ] = array();
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get last inserted Mongo id.
     * 
     * @return string
     */
    public function insertId()
    {
        return $this->last_inserted_id;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Auto add mongo id if "_id" used and .
     * 
     * @param type $string
     * @return \MongoId 
     */
    private function _isMongoId($string = '', $value = '')
    {
        if($this->use_mongo_id)
        {
            if($string == '_id' AND ! is_object($value))
            {
                return new MongoId($value);
            }
        }
        
        return $value;
    }
    
    // --------------------------------------------------------------------
    // Fake functions. Do not remove them.
    
    public function transaction() {}
    public function commit() {}
    public function rollback() {}
    public function lastQuery() {
        // @todo: mongodb query output.
        // any idea ? how can we do it ?
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Close the connection.
     */
    function __destruct()
    {
        if(is_object($this->connection))
        {
            $this->connection->close();
        } 
    }
    
}
// END Mongo_Db Class

/* End of file mongo_db.php */
/* Location: ./ob/mongo_db/releases/0.0.1/mongo_db.php */
