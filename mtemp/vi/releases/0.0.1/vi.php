<?php
namespace vi {
    
    /**
    * View Helper
    *
    * @package     Packages
    * @subpackage  vi
    * @category    View
    * @link
    */
    Class start
    {
        function __construct()
        {
            \log\me('debug', 'Vi Helper Initialized.');
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Load module view file
    *
    * @param string  $filename
    * @param boolean $include or fetch as string
    * @param array   $data
    * @return string | void
    */
    function view($filename, $include = true, $data = '')
    {
        $class = '\\'.getComponent('view');
        return $class::getInstance()->load($filename, $include, $data);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Load view file from MODULES\views folder.
    *
    * @param string  $filename
    * @param boolean $string default false
    * @param array   $data
    * @return string | void
    */
    function views($filename, $include = true, $data = '')
    {
        $class = '\\'.getComponent('view');
        $view  = $class::getInstance();
        $view->setPath('views');
        
        return $view->load($filename, $include, $data);
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Create view variables for views
    * 
    * @param  string $key
    * @param  mixed $val
    * @return  mixed
    */
    function setVar($key, $val = '')
    {
        if(is_string($val))
        {
            $class = '\\'.getComponent('view');
            $view  = $class::getInstance();
            $view->var[$key] = (string)$val;
            return;
        }
        
        if(is_array($val))
        {
            setArray($key, $val);
            return;
        }
        
        if(is_object($val))
        {
            setObject($key, $val);
            return;
        }
    }

    // ------------------------------------------------------------------------

    /**
    * Get the view variables from views
    * 
    * @param  string $key
    * @return mixed
    */
    function getVar($key)
    {
        $class = '\\'.getComponent('view');
        $view  = $class::getInstance();
        
        if(isset($view->var[$key]))
        {
            return $view->var[$key];
        }
        
        if(isset($view->array[$key]))
        {
            return getArray($key);
        }
        
        if(isset($view->object[$key]))
        {
            return getObject($key);
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Create array variables for views
    * 
    * @param  string $key
    * @param  array $val
    * @return  string | null
    */
    function setArray($key, $val = array())
    {
        $class = '\\'.getComponent('view');
        $view  = $class::getInstance();
        
        $val  = (array)$val;
        foreach($val as $value)
        {
            $view->array[$key][] = $value;
        }
        
        return;
    }

    // ------------------------------------------------------------------------

    /**
    * Get array variables from views
    * 
    * @param  string $key
    * @param  array $val
    * @return  string | null
    */
    function getArray($key)
    {
        $class = '\\'.getComponent('view');
        $view  = $class::getInstance();
    
        if(isset($view->array[$key]))
        {
            $var = array();
            foreach($view->array[$key] as $value)
            {
                $var[] = $value;
            }

            return $var;
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Create object variable
     * 
     * @param string $key
     * @param object $val
     * @return void
     */
    function setObject($key, $val = object)
    {
        $class = '\\'.getComponent('view');
        $view  = $class::getInstance();

        $view->object[$key] = $val;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get object variable
     * 
     * @param string $key
     * @return object
     */
    function getObject($key)
    {
        $class = '\\'.getComponent('view');
        $view  = $class::getInstance();
        
        if(isset($view->object[$key]))
        {
            return $view->object[$key];
        }
    }
    
}

/* End of file vi.php */
/* Location: ./packages/vi/releases/0.0.1/vi.php */