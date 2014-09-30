<?php
namespace Utf8\Src {
    
    // ------------------------------------------------------------------------

    /**
    * Strips whitespace (or other UTF-8 characters) from the end of a string.
    * This is a UTF8-aware version of [rtrim](http://php.net/rtrim).
    *
    *     $str = $this->utf8->rtrim($str);
    *
    * @param   string   input string
    * @param   string   string of characters to remove
    * @return  string
    */
    function _rtrim($str, $charlist = null)
    {
        if ($charlist === null)
        {
            return rtrim($str);
        }
        
        if (getInstance()->utf8->isAscii($charlist))
        {
            return rtrim($str, $charlist);   
        }

        $charlist = preg_replace('#[-\[\]:\\\\^/]#', '\\\\$0', $charlist);

        return preg_replace('/['.$charlist.']++$/uD', '', $str);
    }
}

/* End of file _rtrim.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_rtrim.php */