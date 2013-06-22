<?php

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2009.
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

require_once ('drivers'. DS .'pager'. DS .'Pager_common.php');

/**                 
 * Obullo Pager Class
 *
 *
 * @package       Obullo
 * @subpackage    Libraries
 * @category      Libraries
 * @author        Ersin Guvenc
 * @author        Derived from PEAR pager package.
 * @see           Original package http://pear.php.net/package/Pager
 * @link          
 */
Class OB_Pager
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
    
        $classname = 'OB_Pager_' . $mode;
        $classfile = BASE .'libraries'. DS .'drivers'. DS .'pager'. DS .$classname. EXT;

        if ( ! class_exists($classname)) 
        {
            include_once($classfile);
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
/* Location: ./obullo/libraries/Pager.php */