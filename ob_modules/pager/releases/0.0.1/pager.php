<?php

$packages = get_config('packages');
    
require(OB_MODULES .'pager'. DS .'releases'. DS .$packages['dependencies']['pager']['version']. DS .'src'. DS .'pager_common'. EXT);

/**                 
 * Obullo Pager Class
 *
 *
 * @package       Obullo
 * @subpackage    pager
 * @category      pagination
 * @author        Obullo Team
 * @author        Derived from PEAR pager package.
 * @see           Original package http://pear.php.net/package/Pager         
 */

Class Pager
{     
    /**
    * Return a pager based on $mode and $options
    *
    * @param array $options Optional parameters for the storage class
    *
    * @return object Storage object
    * @static
    * @access public
    */
    public function init($options = array())
    {
        $mode = (isset($options['mode']) ? strtolower($options['mode']) : 'jumping');
    
        $classname = 'pager_' . $mode;
        
        if ( ! class_exists($classname)) 
        {
            $packages = get_config('packages');
    
            require_once(OB_MODULES .'pager'. DS .'releases'. DS .$packages['dependencies']['pager']['version']. DS .'src'. DS .$classname. EXT);
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

// END Pager Class

/* End of file Pager.php */
/* Location: ./ob_modules/pager/releases/0.0.1/pager.php */