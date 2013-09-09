<?php

/**
 * Navigation Bar Class
 *
 * A simple navigation link control class.
 *
 * @package       packages
 * @subpackage    navbar
 * @category      navigation
 * @link
 */

Class Navbar {
   
    public $toplevel        = array();
    public $sublevel        = array();
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
        
        $toplevel = array();
        $module   = $uri->routedSegment(0); // * Get routed segments
        
        foreach($this->toplevel as $key => $val)
        {
            $val        = array_keys($val);
            $level      = $val[0];
            $active     = (isset($this->toplevel[$key][$module])) ? ' class="'.$this->top_active_class.'" ' : ' class="'.$this->top_inactive_class.'" ';
            $toplevel[] = anchor($this->toplevel[$key][$level]['url'], $this->toplevel[$key][$level]['label'], $active);
        }
        
        return $toplevel;
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
        
        $sublevel   = array();
        $module     = $uri->routedSegment(0); // * Get routed segments
        $controller = $uri->routedSegment(1);

        if(isset($this->sublevel[$module]))
        {
            foreach($this->sublevel[$module] as $key => $val)
            {
                $active = ($this->sublevel[$module][$key]['key'] == $controller) ? ' class="'.$this->sub_active_class.'" ' : ' class="'.$this->sub_inactive_class.'" ';
                $sublevel[] = anchor($this->sublevel[$module][$key]['url'], $this->sublevel[$module][$key]['label'], $active);
            }
        }
        
        return $sublevel;
    }
    
    // public function sub_sublevel() {} ...
    
    // ------------------------------------------------------------------------
    
    /**
     * Numbers of total top and sub level items.
     *
     * @return int
     */
    public function count($key = 'toplevel')
    {
        return count($this->{$key});
    }
    
}

// END Navbar Class

/* End of file Navbar.php */
/* Location: ./packages/navbar/releases/0.0.1/navbar.php */