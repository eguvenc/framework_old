<?php

/**                 
 * Pager Class
 *
 * @package       packages
 * @subpackage    pager
 * @category      pagination
 * @author        Derived from PEAR pager package.
 * @see           Original package http://pear.php.net/package/Pager         
 */

Class Pager
{     
    /**
    * Returns to pager object
    *
    * @param array $options optional parameters for the pager class
    * @return object driver object
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
/* Location: ./packages/pager/releases/0.0.1/pager.php */