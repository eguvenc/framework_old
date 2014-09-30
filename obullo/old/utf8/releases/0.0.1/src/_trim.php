<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------
    
    /**
    * Strips whitespace (or other UTF-8 characters) from the beginning and
    * end of a string. This is a UTF8-aware version of [trim](http://php.net/trim).
    *
    *     $str = $this->utf8->_trim($str);
    *
    * @param   string   input string
    * @param   string   string of characters to remove
    * @return  string
    */

    /**
    * UTF8 trim
    *
    * @access  private
    * @param   string $str
    * @param   string $charlist
    * @return  string
    */
    function _trim($str, $charlist = null)
    {
        if ($charlist === null)
        {
            return trim($str);
        }

        $utf8 = getInstance()->utf8;

        return $utf8->_ltrim($utf8->_rtrim($str, $charlist), $charlist);
    }

}

/* End of file _trim.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_trim.php */