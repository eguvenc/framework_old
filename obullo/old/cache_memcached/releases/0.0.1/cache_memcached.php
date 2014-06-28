<?php

/**
 * Memcached Caching Class 
 *
 * @package		Packages
 */
Class Cache_Memcached
{
    public $connectionSet;
    public $memcached;

    // ------------------------------------------------------------------------	

    /**
     * Get
     * 
     * @param string $key storage key
     * 
     * @return object
     */
    public function get($key)
    {
        $data = $this->memcached->get($key);

        if (isset($data[0])) {
            return $data[0];
        }

        return $data;
    }

    // ------------------------------------------------------------------------	

    /**
     * Get All Keys
     * 
     * @return object
     */
    public function getAllKeys()
    {
        return $this->memcached->getAllKeys();
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
        if ($this->memcached->get($key)) {
            return true;
        }

        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Set Array
     * 
     * @param array $data
     * @param  int $ttl
     */
    public function setArray($data, $ttl = 60)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $this->memcached->set($k, $v, $ttl);
            }
            return true;
        }
        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Save
     * 
     * @param string $key
     * @return object
     */
    public function set($key, $data = 60, $ttl = 60)
    {
        if ( ! is_array($key)) {
            return $this->memcached->set($key, array($data, time(), $ttl), $ttl);
        }
        $this->setArray($key, $data);
        return;
    }

    // ------------------------------------------------------------------------

    /**
     * Delete
     * 
     * @param string $key
     * @return object
     */
    public function delete($key)
    {
        return $this->memcached->delete($key);
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
    public function replace($key, $data, $ttl = 60)
    {
        return $this->memcached->replace($key, array($data, time(), $ttl), $ttl);
    }

    // ------------------------------------------------------------------------	

    /**
     * Clean all data
     * 
     * @param string $key
     * @return object
     */
    public function flushAll()
    {
        return $this->memcached->flush();
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
        return $this->memcached->getStats();
    }

    // ------------------------------------------------------------------------

    /**
     * Get Meta Data
     * 
     * @param string $key
     * @return object
     */
    public function getMetaData($key)
    {
        $stored = $this->memcached->get($key);

        if (count($stored) !== 3) {
            return false;
        }
        list($data, $time, $ttl) = $stored;
        return array(
            'expire' => $time + $ttl,
            'mtime' => $time,
            'data' => $data
        );
    }

    // ------------------------------------------------------------------------

    /**
     * Connect
     * 
     * @param  string $driver family
     * @return boolean
     */
    public function connect($driver = null)
    {
        if ($driver != null AND strtolower($driver) === 'memcached') {
            $className = ucfirst(strtolower($driver));
            $this->memcached = new $className();

            if (!isset($this->connectionSet['servers']['weight'])) {
                $this->connectionSet['servers']['weight'] = 1;
            }
            foreach ($this->connectionSet['servers'] as $key => $value) {
                if (is_array($value)) {
                    $this->memcached->addServer($value['hostname'], $value['port'], $value['weight']);
                } else {
                    $this->memcached->addServer($this->connectionSet['servers']['hostname'], $this->connectionSet['servers']['port'], $this->connectionSet['servers']['weight']);

                    return true;
                }
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
            throw new Exception($driver . ' is not installed.');

            return false;
        }

        return true;
    }

}

// END Cache_Memcached Class
// 
/* End of file cache_memcached.php */
/* Location: ./packages/cache/releases/0.0.1/cache_memcached.php */