<?php

/**
 * APC Caching Class
 *
 * @package
 * @author
 */
Class Cache_Apc
{
    /**
     * Get
     * 
     * @param string $key
     * @return object
     */
    public function get($key)
    {
        $data = apc_fetch($key);
        return (is_array($data)) ? $data[0] : false;
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
        return apc_exists($key);
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
                $this->set($k, $v, $ttl);
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
    public function set($key, $data, $ttl = 60)
    {
        if ( ! is_array($key)) {
            return apc_store($key, array($data, time(), $ttl), $ttl);
        }
        return $this->setArray($key, $data);
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
        return apc_delete($key);
    }

    // ------------------------------------------------------------------------

    /**
     * Replace
     * 
     * @param string $key
     * @return object
     */
    public function replace($key, $data, $ttl = 60)
    {
        $this->delete($key);
        return apc_store($key, array($data, time(), $ttl), $ttl);
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
        return apc_clear_cache('user');
    }

    // ------------------------------------------------------------------------

    /**
     * Cache Info
     * 
     * @param
     * @return object
     */
    public function cacheInfo($type = NULL)
    {
        return apc_cache_info($type);
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
        $stored = apc_fetch($key);
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
     * Controlling for supporting driver.
     * 
     * @param string $key
     * @return object
     */
    public function isSupported()
    {
        if ( ! extension_loaded('apc') OR ini_get('apc.enabled') != "1") {
            throw new Exception('The APC PHP extension must be loaded to use APC Cache.');
            return false;
        }
        return true;
    }

}

/* End of file cache_apc.php */
/* Location: ./packages/cache/releases/0.0.1/cache_apc.php */