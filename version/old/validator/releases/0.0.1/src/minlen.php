<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Minimum length
     *
     * @access    public
     * @param    string
     * @param    value
     * @return    bool
     */    
    function minLen($str, $val)
    {
        if (preg_match("/[^0-9]/", $val))
        {
            return false;
        }
        
        return (mb_strlen($str) < $val) ? false : true;
    }

}