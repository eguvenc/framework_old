<?php
namespace Validator\Src {

    // --------------------------------------------------------------------

    /**
     * Is Numeric
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function isNumeric($str)
    {
        return ( ! is_numeric($str)) ? false : true;
    }

}