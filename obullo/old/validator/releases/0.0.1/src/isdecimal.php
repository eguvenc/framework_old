<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * is Decimal ?
     *
     * @access   public
     * @param    number
     * @return   bool
     */
    function isDecimal($number)
    {
        return is_numeric($number) && floor($number) != $number;
    }
}