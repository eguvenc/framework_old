<?php

/**
 * Shmop Class
 *
 * Allows to use *nix shared memory
 *
 * @package       packages
 * @subpackage    shmop
 * @category      cache
 * @link
 */

Class Shmop {

    /**
     * Constructor
     */
    public function __construct()
    {   
        global $logger;

        if( ! isset(getInstance()->shmop))
        {
            getInstance()->shmop = $this; // Make available it in the controller $this->shmop->method();
        }
        
        $logger->debug('Shmop Class Initialized');
    }

    /**
     * Read from shared memory, if segment key
     * exists returns to "string"
     * otherwise it returns to "null".
     * 
     * @param  string $storeKey segment key
     * @return null | string
     */
    public function get($storeKey)
    {
        $key    = crc32($storeKey);
        $shm_id = shmop_open($key, 'a', 0644, 0); 

        if ($shm_id)
        {
            $size = shmop_size($shm_id);
            $data = shmop_read($shm_id, 0, $size); // now lets read the string back

            if ( ! $data)
            {
                shmop_delete($shm_id);
                shmop_close($shm_id);
                
                return null;  // returns to "null" if segment not found
            }

            shmop_close($shm_id);

            return $data;  // returns to "string" if segment found
        }

        if( $shm_id != 0)
        {
            shmop_close($shm_id);
        }

        return null;  // returns to "null" if segment not found
    }

    // --------------------------------------------------------------------

    /**
     * Write to memory
     * 
     * @param  string $storeKey 
     * @param  string $cacheData 
     * @return mixed           
     */
    public function set($storeKey, $data, $charset = '')
    {
        global $config;

        $default_charset = (empty($charset)) ? $config['charset'] : $charset;

        $key    = crc32($storeKey);
        $size   = mb_strlen($data, $default_charset);
        $shm_id = shmop_open($key, 'c', 0755, $size);    // Create shared memory block with system id

        if ( ! $shm_id)
        {
            throw new Exception(get_class().' couldn\'t create shared memory segment.');
        }

        $shmop_size        = shmop_size($shm_id);             // Get shared memory block's size
        $shm_bytes_written = shmop_write($shm_id, $data, 0);  // Lets write a test string into shared memory

        if ($shm_bytes_written != $size)
        {
            throw new Exception(get_class().' couldn\'t write the entire length of data.');
        }

        shmop_close($shm_id);  // close the connection

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Delete the memory segment
     * 
     * @param  string $storeKey
     * @return void          
     */
    public function delete($storeKey)
    {
        $key    = crc32($storeKey);
        $shm_id = shmop_open($key, 'a', 0644, 0); 

        shmop_delete($shm_id);
        shmop_close($shm_id);
    }

}

/* End of file Shmop.php */
/* Location: ./packages/shmop/releases/0.0.1/shmop.php */