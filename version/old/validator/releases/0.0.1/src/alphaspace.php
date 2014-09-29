<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Alpha Space
     * 
     * @param string $str  sting
     * @param string $lang "L" for all, or "Latin", "Arabic", "Old_Turkic" see : http://php.net/manual/en/regexp.reference.unicode.php
     *
     * @access   public
     *
     * @return   bool
     */        
    function alphaSpace($str, $lang)
    {
        if (empty($lang)) {
            $lang = 'L';
        }

        return ( ! preg_match('/^[\p{'.$lang.'}\s]+$/u', $str)) ? false : true;
    }
}