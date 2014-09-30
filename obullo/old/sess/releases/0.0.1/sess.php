<?php

/**
 * Session Class
 * 
 * @category  Session
 * @package   Sess
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/sess
 */
Class Sess
{
    public static $driver;

    /**
    * Constructor
    *
    * Sets the variables and runs the compilation routine
    * 
    * @return void
    */
    public function __construct()
    {
        global $logger;
        static $sessionStart = null;

        if ($sessionStart == null) {
            $sess = getConfig('sess');
            
            self::$driver = $sess['driver']();
            self::$driver->init($sess);  // Start the sessions            

            $logger->debug('Sess Class Initialized');
            $logger->debug('$_SESSION: ', self::$driver->getAllData());

            $sessionStart = true;
        }

        getInstance()->sess = self::$driver;  // Available it in the contoller $this->sess->method();
    }
}

/* End of file sess.php */
/* Location: ./packages/sess/releases/0.0.1/sess.php */