<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Validate IP Address
     *
     * @access   public
     * @param    string
     * @return   string
     */
    function validIp($ip)
    {
        $get = new \Get;
        
        return $get->validIp($ip);
    }
    
}