<?php
namespace Utf8\Src;

Class From_Unicode {

    // ------------------------------------------------------------------------

    /**
    * UTF8 fromUnicode
    *
    * @access  private
    * @param  array $arr
    * @return string 
    */
    function start($arr)
    {
        ob_start();

        $keys = array_keys($arr);

        foreach ($keys as $k)
        {
            if (($arr[$k] >= 0) AND ($arr[$k] <= 0x007f))   // ASCII range (including control chars)
            {
                echo chr($arr[$k]);
            }
            elseif ($arr[$k] <= 0x07ff)             // 2 byte sequence
            {
                echo chr(0xc0 | ($arr[$k] >> 6));
                echo chr(0x80 | ($arr[$k] & 0x003f));
            }
            elseif ($arr[$k] == 0xFEFF)             // Byte order mark (skip)
            {
                // nop -- zap the BOM
            }
            elseif ($arr[$k] >= 0xD800 AND $arr[$k] <= 0xDFFF)              // Test for illegal surrogates
            {
                // Found a surrogate
                \log\me('debug', 'UTF8 from_unicode: Illegal surrogate at index: '.$k.', value: '.$arr[$k]);
                return false;
            }
            elseif ($arr[$k] <= 0xffff)              // 3 byte sequence
            {
                echo chr(0xe0 | ($arr[$k] >> 12));
                echo chr(0x80 | (($arr[$k] >> 6) & 0x003f));
                echo chr(0x80 | ($arr[$k] & 0x003f));
            }
            elseif ($arr[$k] <= 0x10ffff)              // 4 byte sequence
            {
                echo chr(0xf0 | ($arr[$k] >> 18));
                echo chr(0x80 | (($arr[$k] >> 12) & 0x3f));
                echo chr(0x80 | (($arr[$k] >> 6) & 0x3f));
                echo chr(0x80 | ($arr[$k] & 0x3f));
            }
            else                // Out of range
            {
                \log\me('debug', 'UTF8 from_unicode: Codepoint out of Unicode range at index: '.$k.', value: '.$arr[$k]);
                return false;
            }
        }

        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }
    
}

/* End of file from_unicode.php */
/* Location: ./packages/utf8/releases/0.0.1/src/from_unicode.php */