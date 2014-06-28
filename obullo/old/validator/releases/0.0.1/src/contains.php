<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Exact length
     *
     * @access   public
     * @param    string
     * @param    value
     * @return   bool
     */    
    function contains($str, $val)
    {
        if(strpos($val, ',') > 0) // Has it comma ? 
        {
            $expectedValues = explode(',', $val);

            if(in_array($str, $expectedValues))
            {
                return true;
            }
        } 
        else 
        {
            if($str == $val)
            {
                return true;
            }
        }

        return false;
    }
}