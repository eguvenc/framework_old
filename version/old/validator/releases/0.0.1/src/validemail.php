<?php
namespace Validator\Src {
    
    // --------------------------------------------------------------------
    
    /**
     * Valid Email
     *
     * @access   public
     * @param    string
     * @return   bool
     */    
    function validEmail($str)
    {
        return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? false : true;
    }

}