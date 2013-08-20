<?php
namespace Utf8\Src;

Class Substr_Replace {

    // ------------------------------------------------------------------------

    /**
    * UTF8 substrReplace
    *
    * @access  private
    * @param   string $str
    * @param   string $replacement
    * @param   int $offset
    * @param   int $length
    * @return  string
    */
    function start($str, $replacement, $offset, $length = null)
    {
        $utf8 = new \Utf8(false);

        if($utf8->isAscii($str))
        {
            return ($length === null) ? substr_replace($str, $replacement, $offset) : substr_replace($str, $replacement, $offset, $length);
        }

        $length = ($length === null) ? $utf8->strlen($str) : (int) $length;

        preg_match_all('/./us', $str, $str_array);
        preg_match_all('/./us', $replacement, $replacement_array);

        array_splice($str_array[0], $offset, $length, $replacement_array[0]);

        return implode('', $str_array[0]);
    }
}

/* End of file substr_replace.php */
/* Location: ./ob/utf8/releases/0.0.1/src/substr_replace.php */