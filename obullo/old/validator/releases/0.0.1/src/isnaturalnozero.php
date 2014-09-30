<?php
namespace Validator\Src {

    // --------------------------------------------------------------------

    /**
     * Is a Natural number, but not a zero  (1,2,3, etc.)
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function isNaturalNoZero($str)
    {
        if ( ! preg_match( '/^[0-9]+$/', $str))
        {
            return false;
        }
        
        if ($str == 0)
        {
            return false;
        }
    
        return true;
    }

}