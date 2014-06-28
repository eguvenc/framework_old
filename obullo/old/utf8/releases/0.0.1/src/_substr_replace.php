<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------
    
    /**
    * Replaces text within a portion of a UTF-8 string. This is a UTF8-aware
    * version of [substr_replace](http://php.net/substr_replace).
    *
    *     $str = $this->utf8->_substr_replace($str, $replacement, $offset);
    *
    * @param   string   input string
    * @param   string   replacement string
    * @param   integer  offset
    * @param   int $length
    * @return  string
    */
    function _substr_replace($str, $replacement, $offset, $length = null)
    {
        $utf8 = getInstance()->utf8;

        if($utf8->isAscii($str))
        {
            return ($length === null) ? substr_replace($str, $replacement, $offset) : substr_replace($str, $replacement, $offset, $length);
        }

        $length = ($length === null) ? $utf8->_strlen($str) : (int) $length;

        preg_match_all('/./us', $str, $str_array);
        preg_match_all('/./us', $replacement, $replacement_array);

        array_splice($str_array[0], $offset, $length, $replacement_array[0]);

        return implode('', $str_array[0]);
    }

}

/* End of file _substr_replace.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_substr_replace.php */