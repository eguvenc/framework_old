<?php
namespace vi {
    
    /**
    * View Helper
    *
    * @package     Obullo
    * @subpackage  Helpers
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
    * @param boolean $string default true
    * @param array   $data
    * @return void
    */
    function view($filename, $string = true, $data = '')
    {
        $class = '\\'.getComponentOf('vi');
        return $class::getInstance()->load($filename, $string, $data);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Load view file from MODULES\views folder.
    *
    * @param string  $filename
    * @param boolean $string default true
    * @param array   $data
    * @return void
    */
    function views($filename, $string = true, $data = '')
    {
        $class = '\\'.getComponentOf('vi');
        $view  = $class::getInstance();
        $view->setPath('views');
        
        return $view->load($filename, $string, $data);
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Create view variables for layouts
    * 
    * @param  string $key
    * @param  string $val
    * @return  string | null
    */
    function setVar($key, $val = '')
    {
        $class = '\\'.getComponentOf('vi');
        $view  = $class::getInstance();
   
        if($val == array())
        {
            return setArray($key, $val);
        }
        
        $view->var[$key][] = $val;

        return;
    }

    // ------------------------------------------------------------------------

    /**
    * Get the view variables for layouts
    * 
    * @param  string $key
    * @return string | null
    */
    function getVar($key)
    {
        $class = '\\'.getComponentOf('vi');
        $view  = $class::getInstance();
        
        if(isset($view->var[$key]))
        {
            $var = '';
            foreach($view->var[$key] as $value)
            {
                $var .= $value;
            }

            return $var;
        }
    }

    // ------------------------------------------------------------------------
    
    /**
    * Create array variables for layouts
    * 
    * @param  string $key
    * @param  array $val
    * @param  boolean $use_layout
    * @param  array $layout_data
    * @return  string | null
    */
    function setArray($key, $val = array())
    {
        $class = '\\'.getComponentOf('vi');
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
    * Get array variables for layouts
    * 
    * @param  string $key
    * @param  array $val
    * @param  boolean $use_layout
    * @param  array $layout_data
    * @return  string | null
    */
    function getArray($key)
    {
        $class = '\\'.getComponentOf('vi');
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
    
}

/* End of file vi.php */
/* Location: ./ob/vi/releases/0.0.1/vi.php */