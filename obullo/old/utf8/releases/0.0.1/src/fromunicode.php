<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------
    
    /**
    * Takes an array of ints representing the Unicode characters and returns a UTF-8 string.
    * Astral planes are supported i.e. the ints in the input can be > 0xFFFF.
    * Occurrances of the BOM are ignored. Surrogates are not allowed.
    *
    *     $str = $this->utf8->fromUnicode($array);
    *
    * The Original Code is Mozilla Communicator client code.
    * The Initial Developer of the Original Code is Netscape Communications Corporation.
    * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer.
    * Ported to PHP by Henri Sivonen <hsivonen@iki.fi>, see http://hsivonen.iki.fi/php-utf8/
    * Slight modifications to fit with phputf8 library by Harry Fuecks <hfuecks@gmail.com>.
    *
    * @param   array    unicode code points representing a string
    * @return  string   utf8 string of characters
    * @return  boolean  false if a code point cannot be found
    */
    function fromUnicode($arr = array())
    {
        global $logger;

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
            elseif ($arr[$k] >= 0xD800 AND $arr[$k] <= 0xDFFF)  // Test for illegal surrogates
            {
                $logger->debug('Utf8->fromUnicode: Illegal surrogate at index: '.$k.', value: '.$arr[$k]); // Found a surrogate

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
                $logger->debug('Utf8->fromUnicode: Codepoint out of Unicode range at index: '.$k.', value: '.$arr[$k]);

                return false;
            }
        }

        $result = ob_get_contents();
        ob_end_clean();
        
        return $result;
    }   
}

/* End of file fromunicode.php */
/* Location: ./packages/utf8/releases/0.0.1/src/fromunicode.php */