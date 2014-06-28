<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Match one field to another
     *
     * @access   public
     * @param    string
     * @param    field
     * @return    bool
     */
    function matches($str, $field)
    {
        if ( ! isset($_REQUEST[$field]))
        {
            return false;                
        }

        return ($str !== $_REQUEST[$field]) ? false : true;
    }

}