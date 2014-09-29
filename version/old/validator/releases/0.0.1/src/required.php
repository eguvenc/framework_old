<?php
namespace Validator\Src {
    
    // --------------------------------------------------------------------
    
    /**
     * Required
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function required($str)
    {
        if ( ! is_array($str))
        {
            return (trim($str) == '') ? false : true;
        }
        else
        {
            return ( ! empty($str));
        }
    }

}