<?php

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo
 * @author          obullo.com
 * @filesource
 * @license
 */

/**
 * Obullo View Helper
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Language
 * @link
 */



/**
* Create view variables for layouts
* 
* @param  string $key
* @param  string $val
* @return  string | NULL
*/
if ( ! function_exists('set_var'))
{
    function set_var($key, $val = '')
    {
        $view = lib('ob/View');
   
        if($val == array())
        {
            return set_array($key, $val);
        }
        
        $view->view_var[$key][] = $val;

        return;
    }
}

// ------------------------------------------------------------------------

/**
* Get the view variables for layouts
* 
* @param  string $key
* @return string | NULL
*/
if( ! function_exists('get_var'))
{
    function get_var($key)
    {
        $view = lib('ob/View');
        
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
if ( ! function_exists('set_array'))
{
    function set_array($key, $val = array())
    {
        $view = lib('ob/View');
        $val  = (array)$val;
        
        foreach($val as $value)
        {
            $view->view_array[$key][] = $value;
        }
        
        return;
    }
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
if( ! function_exists('get_array'))
{
    function get_array($key)
    {
        $view = lib('ob/View');
    
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

// ------------------------------------------------------------------------

/**
* Load local view file
*
* @param string  $filename
* @param array   $data
* @param boolean $string default TRUE
* @return void
*/
if ( ! function_exists('view'))
{
    function view($file_url, $data = '', $string = TRUE)
    {
        $folder = 'views';
        
        if(strpos($file_url, 'ob/') === 0)  // Obullo Core Views
        {
            $file_data = array('filename' => strtolower(substr($file_url, 3)), 'path' => BASE .'views'. DS);
        }
        else
        {
            $file_data = loader::load_file($file_url, $folder, FALSE, FALSE);
        }
        
        return lib('ob/View')->load($file_data['path'], $file_data['filename'], $data, $string, FALSE);
    }
}

/* End of file view.php */
/* Location: ./obullo/helpers/view.php */