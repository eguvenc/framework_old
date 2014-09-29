<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Max length
     *
     * @access    public
     * @param    string
     * @param    value
     * @return    bool
     */    
    function maxLen($str, $val)
    {
        if (preg_match("/[^0-9]/", $val))
        {
            return false;
        }

        return (mb_strlen($str) > $val) ? false : true;        
    }
    
}