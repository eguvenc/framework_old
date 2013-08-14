<?php
namespace Pager;

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
        $classname = 'Pager\Src\Pager_'. ucfirst($mode); 
            
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
}

// END Pager Class

/* End of file Pager.php */
/* Location: ./ob/pager/releases/0.0.1/pager.php */