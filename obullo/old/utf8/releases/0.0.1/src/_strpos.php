<?php
namespace Utf8\Src {
    
    // ------------------------------------------------------------------------
    
    /**
    * Finds position of first occurrence of a UTF-8 string. This is a
    * UTF8-aware version of [strpos](http://php.net/strpos).
    *
    *     $position = $this->utf8->strpos($str, $search);
    *
    * @param   string   haystack
    * @param   string   needle
    * @param   integer  offset from which character in haystack to start searching
    * @return  integer  position of needle
    * @return  boolean  false if the needle is not found
    */
    function _strpos($str, $search, $offset = 0)
    {
        global $config;

        return mb_strpos($str, $search, $offset, $config['charset']);
    }
    
}

/* End of file _strpos.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_strpos.php */