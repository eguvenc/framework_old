<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Alpha
     * 
     * @param string $str  sting
     * @param string $lang "L" for all, or "Latin", "Arabic", "Old_Turkic" see : http://php.net/manual/en/regexp.reference.unicode.php
     *
     * @access   public
     *
     * @return   bool
     */        
    function alpha($str, $lang)
    {
        if (empty($lang)) {
            $lang = 'L';
        }

        return ( ! preg_match('/^[\p{'.$lang.'}]+$/u', $str)) ? false : true;
        // return ( ! preg_match("/^([a-z])+$/i", $str)) ? false : true;
    }
}