<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo
 * @subpackage      Obullo.core
 * @category        core   
 * @author          obullo.com
 * @copyright       Obullo Team
 * @since           Version 1.0
 * @version         0.1
 * @version         0.2 added unset object
 * @filesource
 * @license 
 */

Class OB_Registry {
    
    /** 
    * Registry array of objects 
    * @access private 
    */  
    private static $objs = array();
    private static $instance;
    
    /** 
    * singleton method used to access the object 
    * @access public 
    */  
    public static function instance()
    {        
        if( ! isset(self::$instance))
        {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get stored object.
    * 
    * @access   protected
    * @param    string $key
    * @return   object | NULL.
    */
    protected function get($key)
    {
        if(isset(self::$objs[$key]))
        {
            return self::$objs[$key];
        }
        
        return NULL;
    }

    // --------------------------------------------------------------------
    
    /**
    * Set object.
    * 
    * @access   protected 
    * @param    string $key
    * @param    object $val
    */
    protected function set($key, $val)
    {
        self::$objs[$key] = $val;
    }

    // --------------------------------------------------------------------
    
    /**
    * Unset object.
    * 
    * @access   protected 
    * @param    string $key
    * @param    object $val
    */
    public static function unset_object($key)
    {
        unset(self::$objs[$key]);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get stored object.
    * 
    * @param    string $key
    * @return   object
    */
    public static function get_object($key)
    {
        return self::instance()->get($key);
    }
    
    // --------------------------------------------------------------------

    /**
    * Set class instance.
    * 
    * @param    string $key
    * @param    object $instance
    */
    public static function set_object($key, $instance)
    {
        return self::instance()->set($key, $instance);
    }

}

// END Registry Class

/* End of file Registry.php */
/* Location: ./obullo/core/Registry.php */