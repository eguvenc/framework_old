<?php

/**
 * Db Class
 * 
 * @category  Database
 * @package   Db
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/db
 */
Class Db
{
    /**
     * Database connection variable
     * We can grab it globally. ( Db::$var )
     * 
     * @var string
     */
    public static $var = 'db';

    /** 
     * Database config
     * 
     * @var array
     */
    public static $config = array(); 

    /**
     * $db
     * 
     * @var object
     */
    public $dbo;

    /**
     * Constructor
     * 
     * @param string $var database configuration key
     */
    public function __construct($var = 'db')
    {
        global $logger;

        $this->dbo = $this->connect($var);

        $logger->debug('Db Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Connect to Database
     * 
     * @param string $var database variable name
     * 
     * @return object
     */
    public function connect($var = 'db')
    {
        if (isset(getInstance()->{$var}) AND is_object(getInstance()->{$var})) {
            return getInstance()->{$var};   // Lazy Loading.  
        }

        self::$config = getConfig('database'); // Get configuration

        if ( ! isset(self::$config[$var])) {
            throw new Exception('Undefined database configuration please set configuration for '.$var);
        }

        self::$var = $var;    // Store current database key.
                              // We use it in active record class.

        $closure = self::$config[$var]; // Get database Pdo_Driver(); Object

        $dbo = $closure();  
        $dbo->connect(); // run it

        getInstance()->{$var} = &$dbo;

        return $dbo; // database

    }
}

/* End of file db.php */
/* Location: ./packages/db/releases/0.0.1/db.php */