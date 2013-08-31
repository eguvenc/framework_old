<?php

/**
 * Navigation Bar Class
 *
 * A simple navigation link control class.
 *
 * @package       Ob
 * @subpackage    navbar
 * @category      navigation
 * @link
 */

Class Navbar {
   
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
    public function __construct($no_instance = true, $params = array())
    {           
        if(count($params > 0))
        {
            $this->init($params);
        }
        
        if($no_instance)
        {
            getInstance()->navbar = $this; // Available it in the contoller $this->navbar->method();
        }

        log\me('debug', "Navbar Class Initialized");
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Initialize to Class.
     * 
     * @param array $params
     * @return object
     */
    public function init($params = array())
    {
        $navbar = getConfig('navbar');
        $config = array_merge($navbar , $params);
        
        foreach($config as $key => $val)
        {
            $this->{$key} = $val;
        }
        
        return ($this);
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
    public function topLevel()
    {
        $uriClass = getComponent('uri');
        $uri      = $uriClass::getInstance();
        
        $top_level  = array();
        $module     = $uri->rSegment(0); // * Get routed segments
        
        foreach($this->top_level as $key => $val)
        {
            $val         = array_keys($val);
            $level       = $val[0];
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
    public function subLevel()
    {
        $uriClass = getComponent('uri');
        $uri      = $uriClass::getInstance();
        
        $sub_level  = array();
        $module     = $uri->rSegment(0); // * Get routed segments
        $controller = $uri->rSegment(1);

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
/* Location: ./ob/navbar/releases/0.0.1/navbar.php */