<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------

    /**
    * Strips whitespace (or other UTF-8 characters) from the beginning of
    * a string. This is a UTF8-aware version of [ltrim](http://php.net/ltrim).
    *
    *     $str = $this->utf8->ltrim($str);
    *
    * @param   string   input string
    * @param   string   string of characters to remove
    * @return  string
    */
    function _ltrim($str, $charlist = null)
    {
        if ($charlist === null)
        {
            return ltrim($str);
        }
        
        if (getInstance()->utf8->isAscii($charlist))
        {
            return ltrim($str, $charlist);
        }

        $charlist = preg_replace('#[-\[\]:\\\\^/]#', '\\\\$0', $charlist);

        return preg_replace('/^['.$charlist.']+/u', '', $str);
    }

}

/* End of file _ltrim.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_ltrim.php */