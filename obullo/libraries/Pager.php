<?php
defined('BASE') or exit('Access Denied!');

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
    
        $classname = 'Pager_' . $mode;
                                         
        loader::helper('core/driver');
                                            
        return lib_driver($folder = 'pager', $classname, $options);
    }

}

// END Pager Class

/* End of file Pager.php */
/* Location: ./obullo/libraries/Pager.php */