<?php
namespace Ob\sess {
    
    /**
    * Session Helper
    *
    * @package     Obullo
    * @subpackage  Helpers
    * @category    Sessions
    */
    Class start
    {
        public static $driver;
        
        function __construct($params = array())
        {
            \Ob\log\me('debug', 'Session Helper Initialized.');
            
            static $session_start = NULL;

            if ($session_start == NULL)
            {
                $driver = (isset($params['sess_driver'])) ? $params['sess_driver'] : \Ob\config('sess_driver');

                $Class = '\Ob\Sess_'.ucfirst(strtolower($driver));
                self::$driver = $Class::getInstance();
                self::$driver->start($params); // Start the sessions

                $session_start = TRUE;
            }
        }
        
        public static function get_driver()
        {
            return self::$driver;
        }
    }
    
    function set()
    {
        start::get_driver()->set();
    }
    
    function data()
    {
        start::get_driver()->data();
    }
}

/* End of file view.php */
/* Location: ./ob/view/releases/0.0.1/view.php */