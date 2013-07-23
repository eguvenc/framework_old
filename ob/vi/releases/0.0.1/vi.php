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
    * Load local view file
    *
    * @param string  $filename
    * @param array   $data
    * @param boolean $string default TRUE
    * @return void
    */
    function get($file_url, $data = '', $string = TRUE)
    {
        $file_data = loader::getpath($file_url, 'views', FALSE, FALSE);
        
        return View::getInstance()->load($file_data['path'], $file_data['filename'], $data, $string, FALSE);
    }
    
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
        
        $view->view_var[$key][] = $val;

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
        
        if(isset($view->view_var[$key]))
        {
            $var = '';
            foreach($view->view_var[$key] as $value)
            {
                $var .= $value;
            }

            return $var;
        }
    }

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
            $view->view_array[$key][] = $value;
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
    
        if(isset($view->view_array[$key]))
        {
            $var = array();
            foreach($view->view_array[$key] as $value)
            {
                $var[] = $value;
            }

            return $var;
        }
    }
    
}

/* End of file view.php */
/* Location: ./ob/view/releases/0.0.1/view.php */