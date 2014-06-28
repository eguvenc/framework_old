<?php
namespace Utf8\Src {
    
    // ------------------------------------------------------------------------
    
    /**
    * Finds the length of the initial segment not matching mask. This is a
    * UTF8-aware version of [strcspn](http://php.net/strcspn).
    *
    *     $found = $this->utf8->strcspn($str, $mask);
    *
    * @param   string   input string
    * @param   string   mask for search
    * @param   integer  start position of the string to examine
    * @param   integer  length of the string to examine
    * @return  integer  length of the initial segment that contains characters not in the mask
    */

    /**
     * UTF8 strcspn
     *
     * @access  public
     * @param string $str
     * @param string $mask
     * @param int $offset
     * @param int $length 
     * @return  string
     */
    function _strcspn($str, $mask, $offset = null, $length = null)
    {
        $utf8 = getInstance()->utf8;

        if ($str == '' OR $mask == '')
        {
           return 0; 
        }

        if ($utf8->isAscii($str) AND $utf8->isAscii($mask))
        {
            return ($offset === null) ? strcspn($str, $mask) : (($length === null) ? strcspn($str, $mask, $offset) : strcspn($str, $mask, $offset, $length));
        }

        if ($offset !== null OR $length !== null)
        {
            $str = $utf8->_substr($str, $offset, $length);
        }

        // Escape these characters:  - [ ] . : \ ^ /
        // The . and : are escaped to prevent possible warnings about POSIX regex elements
        $mask = preg_replace('#[-[\].:\\\\^/]#', '\\\\$0', $mask);

        preg_match('/^[^'.$mask.']+/u', $str, $matches);

        return isset($matches[0]) ? $utf8->_strlen($matches[0]) : 0;
    }

}

/* End of file _strcspn.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_strcspn.php */