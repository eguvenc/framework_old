<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Integer
     *
     * @access    public
     * @param    string
     * @return    bool
     */    
    function isInteger($str)
    {
        return (bool)preg_match( '/^[\-+]?[0-9]+$/', $str);
    }
}