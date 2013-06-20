<?php
defined('BASE') or exit('Access Denied!'); 

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @license         public
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Driver Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Obullo Team
 * @link        
 */

// ------------------------------------------------------------------------

/**
* Get library driver file
* 
* @param  string $folder
* @param  string $class
* @param  array  $options construct parameters
* @return string
*/
if( ! function_exists('lib_driver')) 
{
    function lib_driver($folder = '', $class = '', $options = array())
    {
        static $overriden_objects = array();
        
        $Class = ucfirst(strtolower($class));                           
        
        $classfile = BASE .'libraries'. DS .'drivers'. DS .$folder. DS .$Class. EXT;

        if ( ! class_exists($Class)) 
        {
            include_once($classfile);
        }
        
        $classname = 'OB_'.$Class;
        $prefix    = config('subclass_prefix');  // MY_

        // Extension Support
        // --------------------------------------------------------------------
        
        if( ! isset($overriden_objects[$Class])) // Modules extend support
        {
            if(file_exists(APP .'libraries'. DS .'drivers'. DS .$folder. DS .$prefix. $Class. EXT))  // Application extend support
            {
                if ( ! class_exists($prefix. $Class)) 
                {
                    require(APP .'libraries'. DS .'drivers'. DS .$folder. DS .$prefix. $Class. EXT);
                }

                $overriden_objects[$Class] = $Class;

                $classname = $prefix. $Class;
            }
        }
        
        if (class_exists($classname))   // If the class exists, return a new instance of it.  
        {               
            if(count($options) > 0)
            {
                $instance = new $classname($options);
            } 
            else 
            {
                $instance = new $classname(); 
            }

            return $instance;
        }

        return NULL;
    }
}

// --------------------------------------------------------------------

/**
 * Get helper driver file
 * 
 * @param type $folder
 * @param type $helpername
 */
if( ! function_exists('helper_driver')) 
{
    function helper_driver($folder = '', $helpername = '')
    {
        static $overridden_helpers = array();
        
        $prefix = config('subhelper_prefix');
        
        if( ! isset($overridden_helpers[$helpername]))
        {
            if(file_exists(APP .'helpers'. DS .'drivers'. DS .$folder. DS .$prefix. $helpername. EXT))
            {
                include(APP .'helpers'. DS .'drivers'. DS .$folder. DS .$prefix. $helpername. EXT);
                
                loader::$_base_helpers[$prefix .$helpername] = $prefix .$helpername;
            }
        }
        
        include(BASE .'helpers'. DS .'drivers'. DS .$folder. DS .$helpername. EXT); // Include Session Driver
        
        loader::$_base_helpers[$helpername] = $helpername;
    }
}

/* End of file driver.php */
/* Location: ./obullo/helpers/driver.php */