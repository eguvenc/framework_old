<?php

/**
 * Cache Class ( Static )
 *
 * @package       packages
 * @subpackage    cache
 * @category      cache
 * @link
 */
Class Cache
{
    public static $config;
    public static $driver;       // driver instance

    // --------------------------------------------------------------------

    /**
     * Constructor
     * 
     * @param array $config
     */
    public function __construct($config = array())
    {
        global $logger;
        
        if ( ! isset(getInstance()->cache)) {

            self::$config = getConfig('cache');         // app/config/cache.php config
            
            $this->init($config); // Make available it in the controller $this->cache->method()

            getInstance()->cache = self::$driver;
        }
        
        $logger->debug('Cache Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Initalize and grab instance of the auth.
     * 
     * @param array $params
     * @return object
     */
    public function init($params = array())
    {
        if (count($params) > 0) {
            self::$config = array_merge($params, self::$config);
        }

        $driver_name = strtolower(self::$config['driver']);
        $className   = 'Cache_'.ucfirst($driver_name);

        self::$driver = new $className; // call driver

        //----------- Check Driver ----------//

        self::$driver->isSupported(self::$config['driver']); // Checking for supporting driver.

        //----------- Check Driver ----------//

        if (self::$config['driver'] != 'file' OR self::$config['driver'] != 'apc') {  // connect 
            $paramsKey = array('hostname' => 1, 'port' => 2);
            $servers   = array_intersect_key(self::$config['servers'], $paramsKey);

            if ( ! isset($servers['hostname']) OR ! isset($servers['port'])) {
                throw new Exception('A defined hostname could not be found.');
            } else {
                self::$driver->connectionSet = self::$config;
                self::$driver->connect(self::$config['driver']);
            }

            if (self::$config['driver'] == 'redis') {  // Redis Configuration
                if (isset(self::$config['servers']['weight'])) {
                    unset(self::$config['servers']['weight']);
                }
            }
            return true;
        }
    }
}

/* End of file cache.php */
/* Location: ./packages/cache/releases/0.0.1/cache.php */