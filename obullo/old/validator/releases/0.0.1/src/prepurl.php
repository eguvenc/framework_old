<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Prep URL
     *
     * @access    public
     * @param    string
     * @return    string
     */    
    function prepUrl($str = '')
    {
        if ($str == 'http://' OR $str == '')
        {
            return '';
        }
        
        if (substr($str, 0, 7) != 'http://' && substr($str, 0, 8) != 'https://')
        {
            $str = 'http://'.$str;
        }
        
        return $str;
    }

}