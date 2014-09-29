<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Strip Image Tags
     *
     * @access   public
     * @param    string
     * @return   string
     */    
    function stripImageTags($str)
    {
        $str = preg_replace("#<img\s+.*?src\s*=\s*[\"'](.+?)[\"'].*?\>#", "\\1", $str);
        $str = preg_replace("#<img\s+.*?src\s*=\s*(.+?).*?\>#", "\\1", $str);

        return $str;
    }
    
}