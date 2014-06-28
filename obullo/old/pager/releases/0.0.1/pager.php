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
     * Constructor
     *
     * Sets the variables for options.
     * 
     * @param array $options
     * @access    public
     * @return    void
     */
    public function __construct($options = array())
    {
        global $logger;

        $mode = (isset($options['mode']) ? strtolower($options['mode']) : 'jumping');
        $classname = 'Pager_' . ucfirst($mode);

        if (count($options) > 0) {
            $instance = new $classname($options);
        } else {
            $instance = new $classname();
        }

        if ( ! isset(getInstance()->pager)) {
            getInstance()->pager = $instance; // Available it in the contoller $this->auth->method();
        }

        $logger->debug('Pager Class Initialized');
    }

}

// END Pager Class

/* End of file Pager.php */
/* Location: ./packages/pager/releases/0.0.1/pager.php */