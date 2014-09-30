<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Valid Base64
     *
     * Tests a string for characters outside of the Base64 alphabet
     * as defined by RFC 2045 http://www.faqs.org/rfcs/rfc2045
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function validBase64($str)
    {
        return (bool) ! preg_match('/[^a-zA-Z0-9\/\+=]/', $str);
    }

}