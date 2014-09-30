<?php
namespace Validator\Src {

    // --------------------------------------------------------------------

    /**
     * Is a Natural number  (0,1,2,3, etc.)
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function isNatural($str)
    {
        return (bool)preg_match( '/^[0-9]+$/', $str);
    }
    
}