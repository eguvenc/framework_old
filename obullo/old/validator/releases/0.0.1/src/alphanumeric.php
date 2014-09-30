<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Alpha-numeric
     *
     * @param string $str  sting
     * @param string $lang "L" for all, or "Latin", "Arabic", "Old_Turkic" see : http://php.net/manual/en/regexp.reference.unicode.php
     *
     * @access   public
     *
     * @return   bool
     */    
    function alphaNumeric($str, $lang)
    {
        if (empty($lang)) {
            $lang = 'L';
        }

        return ( ! preg_match('/^[\p{'.$lang.'}0-9]+$/u', $str)) ? false : true;
        // return ( ! preg_match("/^([a-z0-9])+$/i", $str)) ? false : true;
    }

}