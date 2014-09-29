<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Alpha-numeric with underscores and dashes
     *
     * @param string $str  sting
     * @param string $lang "L" for all, or "Latin", "Arabic", "Old_Turkic" see : http://php.net/manual/en/regexp.reference.unicode.php
     *
     * @access   public
     *
     * @return   bool
     */    
    function alphaDash($str, $lang)
    {
        if (empty($lang)) {
            $lang = 'L';
        }

        return ( ! preg_match('/^[\p{'.$lang.'}_-\d]+$/u', $str)) ? false : true;
        // return ( ! preg_match("/^([-a-z0-9_-])+$/i", $str)) ? false : true;
    }

}