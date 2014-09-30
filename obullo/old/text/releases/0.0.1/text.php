<?php

/**
 * Text Helper
 *
 * @package       packages
 * @subpackage    text
 * @category      text
 * @link        
 * 
 */

Class Text {

    // ------------------------------------------------------------------------

    public function __call($method, $arguments)
    {
        global $packages;

        if( ! function_exists('Text\Src\\'.$method))
        {
            require PACKAGES .'text'. DS .'releases'. DS .$packages['dependencies']['text']['version']. DS .'src'. DS .mb_strtolower($method). EXT;
        }

        return call_user_func_array('Text\Src\\'.$method, $arguments);
    }
}

/* End of file text.php */
/* Location: ./packages/text/releases/0.0.1/text.php */