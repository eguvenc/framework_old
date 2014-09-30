<?php
namespace Validator\Src {

    // --------------------------------------------------------------------

    /**
     * Check string is contain
     * space
     *
     * @param string $str
     * @return bool
     */
    function noSpace($str)
    {
       return (preg_match("#\s#", $str)) ? false : true;
    } 

}