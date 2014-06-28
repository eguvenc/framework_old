<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------

    /**
    * Tests whether a string contains only 7-bit ASCII bytes. This is used to
    * determine when to use native functions or UTF-8 functions.
    *
    *     $ascii = $this->utf8->isAscii($str);
    *
    * @param   mixed    string or array of strings to check
    * @return  boolean
    */
    function isAscii($str)
    {
        if (is_array($str))
        {
            $str = implode($str);
        }

        return ! preg_match('/[^\x00-\x7F]/S', $str);
    }

}

/* End of file isascii.php */
/* Location: ./packages/utf8/releases/0.0.1/src/isascii.php */