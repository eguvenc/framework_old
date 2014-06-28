<?php
namespace Utf8\Src {
    
    // ------------------------------------------------------------------------
    
    /**
    * Case-insenstive UTF-8 version of strstr. Returns all of input string
    * from the first occurrence of needle to the end. This is a UTF8-aware
    * version of [stristr](http://php.net/stristr).
    *
    *     $found = $this->utf8->stristr($str, $search);
    *
    * @param   string  input string
    * @param   string  needle
    * @return  string  matched substring if found
    * @return  false   if the substring was not found
    */

    /**
    * Utf8 stristr
    *
    * @access  public
    * @param   string $str
    * @param   string $search
    * @return  string
    */
    function _stristr($str, $search)
    {
        $utf8 = getInstance()->utf8;
        
        if($utf8->isAscii($str) AND $utf8->isAscii($search))
        {
            return stristr($str, $search);
        }
                
        if($search == '')
        {
            return $str;
        }

        $str_lower    = $utf8->_strtolower($str);
        $search_lower = $utf8->_strtolower($search);

        preg_match('/^(.*?)'.preg_quote($search_lower, '/').'/s', $str_lower, $matches);

        if(isset($matches[1]))
        {
            return substr($str, strlen($matches[1]));
        } 

        return false;
    }   
}

/* End of file _stristr.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_stristr.php */