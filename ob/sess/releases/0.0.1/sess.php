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

                $Class = '\Ob\Sess\Src\Sess_'.ucfirst(strtolower($driver));
                self::$driver = $Class::getInstance();
                self::$driver->init($params); // Start the sessions

                $session_start = TRUE;
            }
        }
    }
    
    function getInstance()
    {
        return start::$driver;
    }
    
    // --------------------------------------------------------------------

    /**
    * Destroy the current session
    *
    * @access    public
    * @return    void
    */
    function destroy()
    {
        getInstance()->destroy();
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Fetch a specific item from the session array
    *
    * @access   public
    * @param    string
    * @return   string
    */ 
    function get($key, $prefix = '')
    {
        return getInstance()->get($key, $prefix);
    }
    
    // --------------------------------------------------------------------

    /**
    * Add or change data in the "userdata" array
    *
    * @access   public
    * @param    mixed
    * @param    string
    * @return   void
    */ 
    function alldata()
    {
        return getInstance()->alldata();
    }
    
    // --------------------------------------------------------------------

    /**
    * Add or change data in the "userdata" array
    *
    * @access   public
    * @param    mixed
    * @param    string
    * @return   void
    */ 
    function set($key = '', $val = '', $prefix = '')
    {
        getInstance()->set($key, $val, $prefix);
    }
    
    // --------------------------------------------------------------------

    /**
    * Delete a session variable from the "userdata" array
    *
    * @access    array
    * @return    void
    */     
    function remove($key = array(), $prefix = '')
    {
        getInstance()->remove($key, $prefix);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Add or change flashdata, only available
    * until the next request
    *
    * @access   public
    * @param    mixed
    * @param    string
    * @return   void
    */
    function set_flash($key = array(), $val = '')
    {
        return getInstance()->remove($key, $val);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Fetch a specific flashdata item from the session array
    *
    * @access   public
    * @param    string  $key you want to fetch
    * @param    string  $prefix html open tag
    * @param    string  $suffix html close tag
    * @return   string
    */
    function get_flash($key, $prefix = '', $suffix = '')
    {
        return getInstance()->get_flash($key, $prefix, $suffix);
    }
    
    // --------------------------------------------------------------------

    /**
    * Keeps existing flashdata available to next request.
    *
    * @access   public
    * @param    string
    * @return   void
    */
    function keep_flash($key)
    {
        return getInstance()->keep_flash($key);
    }
}

/* End of file sess.php */
/* Location: ./ob/sess/releases/0.0.1/sess.php */