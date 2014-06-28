<?php

/**
 * Redis Caching Class
 *
 * @package
 */
Class Cache_Redis
{
    public $connectionSet;
    public $redis;

    // ------------------------------------------------------------------------ 

    /**
     * Method to determine if a phpredis object thinks it's connected to a server
     * 
     * @return boolean true or false
     */
    public function IsConnected()
    {
        return $this->redis->IsConnected();
    }

    // ------------------------------------------------------------------------ 

    /**
     * Get last error
     * 
     * @return string with the last returned script based error message, or NULL if there is no error
     */
    public function getLastError()
    {
        return $this->redis->getLastError();
    }

    // ------------------------------------------------------------------------ 

    /**
     * Get last save
     * 
     * @return timestamp the timestamp of the last disk save.
     */
    public function getLastSave()
    {
        return $this->redis->lastSave();
    }

    // ------------------------------------------------------------------------ 

    /**
     * Returns the type of data pointed by a given type key.
     * 
     * @param  string $typeKey string | set | 
     * @return Depending on the type of the data pointed by the type key
     */
    public function setType($typeKey)
    {
        return $this->redis->type($typeKey);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Sets an expiration date (a timeout) on an item. pexpire requires a TTL in milliseconds.
     *
     * @param string $key
     * @param int $ttl
     * @return boolean true or false
     */
    public function setTimeout($key, $ttl)
    {
        return $this->redis->setTimeout($key, $ttl);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Get
     * 
     * @param string $key
     * @return object
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Remove all keys from all databases. 
     * 
     * @return boolean always true
     */
    public function flushAll()
    {
        return $this->redis->flushAll();
    }

    // ------------------------------------------------------------------------ 

    /**
     * Remove all keys from the current database.
     * 
     * @return boolean always true
     */
    public function flushDB()
    {
        return $this->redis->flushDB();
    }

    // ------------------------------------------------------------------------ 

    /**
     * Append specified string to the string stored in specified key.
     * 
     * @param string $key
     * @param string $data
     * 
     * @return object
     */
    public function append($key, $data)
    {
        return $this->redis->append($key, $data);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Verify if the specified key exists.
     * 
     * @param string $key
     * @return boolean true or false
     */
    public function keyExists($key)
    {
        return $this->redis->exists($key);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Get the values of all the specified keys. If one or more keys dont exist, the array will contain
     * 
     * @param  array $key
     * @return array containing the list of the keys
     */
    public function getMultiple($key)
    {
        if ( ! is_array($key)) {
            return false;
        }
        return $this->redis->mGet($key);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Sets a value and returns the previous entry at that key.
     * 
     * @param  string $key
     * @param  string $data
     * @return string the previous value located at this key.
     */
    public function getSet($key, $data)
    {
        return $this->redis->getSet($key, $data);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Renames a key.
     * 
     * @param  string $key
     * @param  string $newKey
     * @return boolean true or false
     */
    public function renameKey($key, $newKey)
    {
        return $this->redis->rename($key, $newKey);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Returns the keys that match a certain pattern.
     * 
     * @param  string $pattern
     * @return array the keys that match a certain pattern.
     */
    public function getAllKeys($pattern = '*')
    {
        return $this->redis->keys($pattern);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Get All Data
     * 
     * @return array return all the key and data
     */
    public function getAllData()
    {
        $keys = $this->redis->keys('*');
        if (sizeof($keys) == 0) {
            return $keys;
        }
        foreach ($keys as $k => $v) {
            $getData = $this->redis->get($v);

            if (empty($getData)) {
                $getData = $this->sGetMembers($v);
            }
            $data[$v] = $getData;
        }
        return $data;
    }

    // ------------------------------------------------------------------------ 

    /**
     * Sort the elements in a list, set or sorted set.
     * 
     * @param  string $key
     * @param  array $sort optional
     * @return array the keys that match a certain pattern.
     */
    public function sort($key, $sort = array())
    {
        if (count($sort) > 0) {
            return $this->redis->sort($key, $sort);
        }
        return $this->redis->sort($sort);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Adds a value to the set value stored at key. If this value is already in the set, FALSE is returned.
     * 
     * @param  string $key
     * @param  string $data
     * @return long the number of elements added to the set.
     */
    public function sAdd($key, $data)
    {
        if (is_array($data)) {
            $data = "'" . implode("','", $data) . "'";
        }
        return $this->redis->sAdd($key, $data);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Returns the cardinality of the set identified by key.
     * 
     * @param  string $key
     * @return long the cardinality of the set identified by key, 0 if the set doesn't exist.
     */
    public function sSize($key)
    {
        return $this->redis->sCard($key);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Returns the members of a set resulting from the intersection of all the sets held at the specified keys.
     * 
     * @param  array $key
     * @return array contain the result of the intersection between those keys. If the intersection beteen the different sets is empty, the return value will be empty array.
     */
    public function sInter($key = array())
    {
        if (count($key) > 0 AND is_array($key)) {
            $keys = "'" . implode("','", $key) . "'";

            return $this->redis->sInter($keys);
        }

        return false;
    }

    // ------------------------------------------------------------------------ 

    /**
     * Returns the contents of a set.
     * 
     * @param  string $key
     * @return array of elements, the contents of the set.
     */
    public function sGetMembers($key)
    {
        return $this->redis->sMembers($key);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Adds a value to the hash stored at key. If this value is already in the hash, FALSE is returned.
     * 
     * @param  string $key
     * @param  string $hashKey
     * @param  string $data
     * @return LONG 1 if value didn't exist and was added successfully, 0 if the value was already present and was replaced, FALSE if there was an error.
     */
    public function hSet($key, $hashKey, $data)
    {
        return $this->redis->hSet($key, $hashKey, $data);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Adds a value to the hash stored at key only if this field isn't already in the hash.
     * 
     * @param  string $key
     * @param  string $hashKey
     * @return string The value, if the command executed successfully BOOL FALSE in case of failure.
     */
    public function hGet($key, $hashKey)
    {
        return $this->redis->hGet($key, $hashKey);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Returns the length of a hash, in number of items
     * 
     * @param  string $key
     * @return LONG the number of items in a hash, FALSE if the key doesn't exist or isn't a hash.
     */
    public function hLen($key)
    {
        return $this->redis->hLen($key);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Removes a value from the hash stored at key. If the hash table doesn't exist, or the key doesn't exist, FALSE is returned.
     * 
     * @param  string $key
     * @param  string $hashKey
     * @return BOOL TRUE in case of success, FALSE in case of failure
     */
    public function hDel($key, $hashKey)
    {
        return $this->redis->hDel($key, $hashKey);
    }  

    // ------------------------------------------------------------------------ 

    /**
     * Returns the keys in a hash, as an array of strings.
     * 
     * @param  string $key
     * @return An array of elements, the keys of the hash. This works like PHP's array_keys().
     */
    public function hKeys($key)
    {
        return $this->redis->hKeys($key);
    }   

    // ------------------------------------------------------------------------ 

    /**
     * Returns the values in a hash, as an array of strings.
     * 
     * @param  string $key
     * @return An array of elements, the values of the hash. This works like PHP's array_values().
     */
    public function hVals($key)
    {
        return $this->redis->hVals($key);
    }


    // ------------------------------------------------------------------------ 

    /**
     * Verify if the specified member exists in a key.
     * 
     * @param  string $key
     * @param  string $memberKey
     * @return BOOL: If the member exists in the hash table, return TRUE, otherwise return FALSE.
     */
    public function hGetAll($key)
    {
        return $this->redis->hGetAll($key);
    }
    
    // ------------------------------------------------------------------------ 

    /**
     * Increments the value of a member from a hash by a given amount.
     * 
     * @param  string $key
     * @param  string $member
     * @param  integer $value
     * @return LONG the new value
     */
    public function hIncrBy($key, $member, $value)
    {
        return $this->redis->hIncrBy($key, $member, $value);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Increments the value of a hash member by the provided float value
     * 
     * @param  string $key
     * @param  string $member
     * @param  float $value
     * @return FLOAT the new value
     */
    public function hIncrByFloat($key, $member, $value)
    {
        return $this->redis->hIncrByFloat($key, $member, $value);
    }   

    // ------------------------------------------------------------------------ 

    /**
     * Fills in a whole hash. Non-string values are converted to string, using the standard (string) cast. NULL values are stored as empty strings.
     * 
     * @param  string $key
     * @param  array $members key->value array
     * @return BOOL
     */
    public function hMSet($key, $members)
    {
        return $this->redis->hMSet($key, $members);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Retrieve the values associated to the specified fields in the hash.
     * 
     * @param  string $key
     * @param  array $members key->value array
     * @return Array An array of elements, the values of the specified fields in the hash, with the hash keys as array keys.
     */
    public function hMGet($key, $memberKeys)
    {
        return $this->redis->hMGet($key, $memberKeys);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Authenticate the connection using a password. Warning: The password is sent in plain-text over the network.
     * 
     * @param  string $password
     * @return boolean true or false
     */
    public function auth($password)
    {
        return $this->redis->auth($password);
    }

    // ------------------------------------------------------------------------ 

    /**
     * Set client option.
     * 
     * @param  string $option 'SERIALIZER_NONE' | 'SERIALIZER_PHP' | 'SERIALIZER_IGBINARY'
     * @return boolean true or false
     */
    public function setOption($option)
    {
        switch ($option) {
            case 'SERIALIZER_NONE': // don't serialize data
                return $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
                break;
            case 'SERIALIZER_PHP': // use built-in serialize/unserialize
                $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
                return true;
                break;
            case 'SERIALIZER_IGBINARY': // use igBinary serialize/unserialize
                return $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_IGBINARY);
                break;

            default:
                return false;
                break;
        }
    }

    // ------------------------------------------------------------------------ 

    /**
     * Get client option.
     * 
     * @return string value
     */
    public function getOption()
    {
        return $this->redis->getOption(\Redis::OPT_SERIALIZER);
    }

    // ------------------------------------------------------------------------

    /**
     * Set Array
     * 
     * @param array $data
     * @param int $ttl
     */
    public function setArray($data, $ttl)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $this->redis->set($k, $v, $ttl);
            }
            return $this;
        }

        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Set
     * 
     * @param string $key
     * @param string or array $data
     * @param int $ttl
     */
    public function set($key = '', $data = 60, $ttl = 60) // If empty $ttl default timeout unlimited
    {
        if ( ! is_array($key)) {
            return $this->redis->set($key, $data, $ttl);
        }
        $this->setArray($key, $data);
        return;
    }

    // ------------------------------------------------------------------------

    /**
     * Delete remove specified keys.
     * 
     * @param string $key
     * @return object
     */
    public function delete($key)
    {
        return $this->redis->delete($key);
    }

    // ------------------------------------------------------------------------

    /**
     * Replace key value
     * 
     * @param  string $key
     * @param  string or array $data
     * @param  int $ttl sec
     * @return replace new value
     */
    public function replace($key, $data, $ttl = null)
    {
        return $this->set($key, $data, $ttl);
    }

    // ------------------------------------------------------------------------

    /**
     * Cache Info
     * 
     * @param
     * @return object
     */
    public function cacheInfo()
    {
        return $this->redis->info();
    }

    // ------------------------------------------------------------------------

    /**
     * Get Meta Data
     * 
     * @param string $key
     * 
     * @return object
     */
    public function getMetaData($key)
    {
        return false;
    }

    // ------------------------------------------------------------------------

    public function connect($driver = null)
    {
        if ($driver != null AND (strtolower($driver) === 'redis')) {
            $className = ucfirst(strtolower($driver));
            $this->redis = new $className();

            if (isset($this->connectionSet['servers']['timeout'])) {
                $this->redis->connect($this->connectionSet['servers']['hostname'], $this->connectionSet['servers']['port'], $this->connectionSet['servers']['timeout']);
            } else {
                $this->redis->connect($this->connectionSet['servers']['hostname'], $this->connectionSet['servers']['port']);
            }
            if (isset($this->connectionSet['auth'])) {
                $this->auth($this->connectionSet['auth']);
            }
            return true;
        }

        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Controlling for supporting driver.
     * 
     * @param string $key
     * @return object
     */
    public function isSupported($driver)
    {
        if ( ! extension_loaded($driver)) {
            throw new Exception(ucfirst($driver) . ' driver is not installed.');

            return false;
        }

        return true;
    }

}

// END Cache_Redis Class

/* End of file cache_redis.php */
/* Location: ./packages/cache/releases/0.0.1/cache_redis.php */