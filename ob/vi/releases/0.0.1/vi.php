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
    * @param array   $data
    * @param boolean $string default true
    * @return void
    */
    function view($filename, $data = '', $string = true)
    {
        return \View::getInstance()->load($filename, $data, $string);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Load view file from MODULES\views folder.
    *
    * @param string  $filename
    * @param array   $data
    * @param boolean $string default true
    * @return void
    */
    function views($filename, $data = '', $string = true)
    {
        $view = \View::getInstance();
        $view->setPath('views');
        
        return $view->load($filename, $data, $string);
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
        $view = \View::getInstance();
   
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
        $view = \View::getInstance();
        
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
        $view = \View::getInstance();
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
        $view = \View::getInstance();
    
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