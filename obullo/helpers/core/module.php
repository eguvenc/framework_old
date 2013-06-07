<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009 - 2012.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo
 * @author          obullo.com
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Module Helper
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @link        
 */


/**
* Load Sub-module and Module config files.
*
* @access   public
* @return   void
*/
function module_init()
{
    module_init_autoloaders(); // Init Obullo Autoloader files
    module_init_autoruns();    // Init Obullo Autorun files
}

// ------------------------------------------------------------------------

/**
* Merge Module Autoloaders
*
* @access   public
* @return   void
*/
function module_init_autoloaders()
{
    $autoload = module_merge_autoloaders('autoload', '', 'Autoloaders');

    if(is_array($autoload))
    {
        loader::helper('ob/array');

        foreach(array_keys($autoload) as $key)
        {
            if(count($autoload[$key]) > 0)
            {
                if( ! is_assoc_array($autoload[$key]))
                {
                    throw new Exception("Please redefine your $key autoload variables, they must be associative array ! 
                            An example configuration <b>\$autoload['helper'] = array('ob/session' => '', 'ob/form' => '')</b>");
                }

                foreach($autoload[$key] as $filename => $args)
                {
                    if( ! is_string($filename))
                    {
                        throw new Exception("Autoload function error, autoload array must be associative. 
                            An example configuration <b>\$autoload['helper'] = array('ob/session' => '', 'ob/form' => '')</b>");
                    }

                    if(is_array($args)) // if arguments exists
                    { 
                        switch($key)
                        {
                            case ($key == 'config' || $key == 'lang' || $key == 'lib' || $key == 'model'):

                                $third_param = isset($args[1]) ? $args[1] : FALSE;

                                loader::$key($filename, $args[0], $third_param);
                                break;

                            case 'helper':

                                loader::$key($filename, $args[0]);
                                break;
                        }
                    }
                    else
                    {
                        loader::$key($filename);
                    }
                }
            }
        }
    }
}

// -------------------------------------------------------------------- 

/**
* Initalize to Obullo autoloaders and
* merge autoload, autorun file variables to application module.
* 
* @access private
* @return void
*/
function module_init_autoruns()
{
    $autorun = module_merge_autoloaders('autorun', '', 'Autorun');

    unset($autorun['mode']);

    if(isset($autorun['function']))
    {
        if(count($autorun['function']) > 0)
        {
            foreach(array_reverse($autorun['function']) as $function => $arguments)
            {
                if( ! function_exists($function))
                {
                    throw new Exception('The autorun function '. $function . ' not found, please define it in 
                        APP/config/autoload.php or MODULES/'.lib('ob/Router')->fetch_directory().'/config/');
                }

                call_user_func_array($function, $arguments);   // Run autorun function.
            }
        }
    }  
}

// -------------------------------------------------------------------- 

/**
* Merge Modules autoload, autorun ..
* file variables to application module.
* 
* @access private
* @param string $file
* @param string $var
* @param string $type
* @return array
*/
function module_merge_autoloaders($file = 'autoload', $var = '', $type = 'Autoloaders')
{  
    $values = get_static($file, $var, APP .'config');
    
    log_me('debug', 'Application '.$type.' Initialized');
    
    return $values;
}

/* End of file module.php */
/* Location: ./obullo/helpers/core/module.php */