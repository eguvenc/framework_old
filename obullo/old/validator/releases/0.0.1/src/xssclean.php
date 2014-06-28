<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * XSS Clean
     *
     * @access   public
     * @param    string
     * @return   string
     */    
    function xssClean($str)
    {   
        if( ! isset(getInstance()->security))
        {
            new \Security;
        }

        return getInstance()->security->xssClean($str);
    }
    
}