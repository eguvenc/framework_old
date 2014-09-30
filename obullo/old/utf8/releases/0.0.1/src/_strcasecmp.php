<?php
namespace Utf8\Src {
    
    // ------------------------------------------------------------------------
    
    /**
    * Case-insensitive UTF-8 string comparison. This is a UTF8-aware version
    * of [strcasecmp](http://php.net/strcasecmp).
    *
    *     $compare = $this->utf8->_strcasecmp($str1, $str2);
    *
    * @param   string   string to compare
    * @param   string   string to compare
    * @return  integer  less than 0 if str1 is less than str2
    * @return  integer  greater than 0 if str1 is greater than str2
    * @return  integer  0 if they are equal
    */

    /**
     * utf8 _strcasecmp
     *
     * @access  public
     * @param   string $str1
     * @param   string $str2
     * @return string
     */
    function _strcasecmp($str1, $str2)
    {
        $utf8 = getInstance()->utf8;

        if ($utf8->isAscii($str1) AND $utf8->isAscii($str2))
        {
            return strcasecmp($str1, $str2);
        }

        $str1 = $utf8->_strtolower($str1);
        $str2 = $utf8->_strtolower($str2);
        
        return strcmp($str1, $str2);
    }

}

/* End of file _strcasecmp.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_strcasecmp.php */