<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009 - 2012.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo
 * @author          obullo.com
 * @copyright       Obullo Team
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Navigation Bar Class v1.0
 *
 * A simple navigation link control class.
 *
 * @package       Obullo
 * @subpackage    Libraries
 * @category      Libraries
 * @author        Obullo
 * @link
 */

Class OB_Navbar {
   
    public $top_level        = array();
    public $sub_level        = array();
    public $top_active_class    = 'navbar-top-active';
    public $top_inactive_class  = 'navbar-top-inactive';
    public $sub_active_class    = 'navbar-sub-active';
    public $sub_inactive_class  = 'navbar-sub-inactive';
    
    /**
    * Constructor
    *
    * Sets the variables and runs the compilation routine.
    *
    * @version   0.1
    * @access    public
    * @return    void
    */
    public function __construct($params = array())
    {   
        if(isset($params['module']))  // Submodule support
        {
            $navbar = get_config('navbar', '', MODULES .$params['module']. DS .'config');
            $config = array_merge($navbar , $params);
        } 
        else 
        {
            $auth   = get_config('navbar');
            $config = array_merge($auth , $params);
        }
        
        foreach($config as $key => $val)
        {
            $this->{$key} = $val;
        }

        log_me('debug', "Navbar Class Initialized");
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get top level navigation items using 
     * the first module segment(0).
     * 
     * Checks,
     * $this->uri->segment(0)  // http://example.com/users/
     * 
     * @return array 
     */
    public function top_level()
    {
        $top_level  = array();
        $module     = lib('ob/Uri')->rsegment(0); // * Get routed segments
        
        foreach($this->top_level as $key => $val)
        {
            $val   = array_keys($val);
            $level = $val[0];
            $active      = (isset($this->top_level[$key][$module])) ? ' class="'.$this->top_active_class.'" ' : ' class="'.$this->top_inactive_class.'" ';
            $top_level[] = anchor($this->top_level[$key][$level]['url'], $this->top_level[$key][$level]['label'], $active);
        }
        
        return $top_level;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get sub level navigation items using 
     * the second module segment(1).
     * 
     * Checks, 
     * $this->uri->segment(0)  // http://example.com/users/
     * $this->uri->segment(1)  // http://example.com/users/add
     * 
     * @return array
     */
    public function sub_level()
    {
        $sub_level  = array();
        $module     = lib('ob/Uri')->rsegment(0); // * Get routed segments
        $controller = lib('ob/Uri')->rsegment(1);

        if(isset($this->sub_level[$module]))
        {
            foreach($this->sub_level[$module] as $key => $val)
            {
                $active = ($this->sub_level[$module][$key]['key'] == $controller) ? ' class="'.$this->sub_active_class.'" ' : ' class="'.$this->sub_inactive_class.'" ';
                $sub_level[] = anchor($this->sub_level[$module][$key]['url'], $this->sub_level[$module][$key]['label'], $active);
            }
        }
        
        return $sub_level;
    }
    
    // public function sub_sub_level() {} ...
    
    // ------------------------------------------------------------------------
    
    /**
     * Numbers of total top and sub level items.
     *
     * @return int
     */
    public function count($key = 'top_level')
    {
        return count($this->{$key});
    }
    
}

// END Navbar Class

/* End of file Navbar.php */
/* Location: ./obullo/libraries/Navbar.php */