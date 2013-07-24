<?php
namespace Ob\vi {
    
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
            // 
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Load module view file
    *
    * @param string  $filename
    * @param array   $data
    * @param boolean $string default TRUE
    * @return void
    */
    function get($filename, $data = '', $string = TRUE)
    {
        return \Ob\View::getInstance()->load($filename, $data, $string);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Load view file form MODULES\views folder.
    *
    * @param string  $filename
    * @param array   $data
    * @param boolean $string default TRUE
    * @return void
    */
    function views($filename, $data = '', $string = TRUE)
    {
        $view = \Ob\View::getInstance();
        $view->set_path(); // set views path
        
        return get($filename, $data, $string);
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Create view variables for layouts
    * 
    * @param  string $key
    * @param  string $val
    * @return  string | NULL
    */
    function set_var($key, $val = '')
    {
        $view = View::getInstance();
   
        if($val == array())
        {
            return set_array($key, $val);
        }
        
        $view->var[$key][] = $val;

        return;
    }

    // ------------------------------------------------------------------------

    /**
    * Get the view variables for layouts
    * 
    * @param  string $key
    * @return string | NULL
    */
    function get_var($key)
    {
        $view = View::getInstance();
        
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
    * @return  string | NULL
    */
    function set_array($key, $val = array())
    {
        $view = View::getInstance();
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
    * @return  string | NULL
    */
    function get_array($key)
    {
        $view = View::getInstance();
    
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

/* End of file view.php */
/* Location: ./ob/view/releases/0.0.1/view.php */