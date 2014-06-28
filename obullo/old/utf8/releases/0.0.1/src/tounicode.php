<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------
    
    /**
    * Takes an UTF-8 string and returns an array of ints representing the Unicode characters.
    * Astral planes are supported i.e. the ints in the output can be > 0xFFFF.
    * Occurrences of the BOM are ignored. Surrogates are not allowed.
    *
    *     $array = $this->utf8->toUnicode($str);
    *
    * The Original Code is Mozilla Communicator client code.
    * The Initial Developer of the Original Code is Netscape Communications Corporation.
    * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer.
    * Ported to PHP by Henri Sivonen <hsivonen@iki.fi>, see <http://hsivonen.iki.fi/php-utf8/>
    * Slight modifications to fit with phputf8 library by Harry Fuecks <hfuecks@gmail.com>
    *
    * @param   string  UTF-8 encoded string
    * @return  array   unicode code points
    * @return  false   if the string is invalid
    */

    /**
    * UTF8 toUnicode
    *
    * @access  private
    * @param   string $str
    * @return  string
    */
    function toUnicode($str)
    {
        // Cached expected number of octets after the current octet until the beginning of the next UTF8 character sequence
        $m_state = 0;
        // Cached Unicode character
        $m_ucs4  = 0;
        // Cached expected number of octets in the current sequence
        $m_bytes = 1;

        $out = array();
        $len = strlen($str);

        for ($i = 0; $i < $len; $i++)
        {
            $in = ord($str[$i]);
            if ($m_state == 0)
            {
                // When m_state is zero we expect either a US-ASCII character or a multi-octet sequence.
                if (0 == (0x80 & $in))
                {
                    // US-ASCII, pass straight through.
                    $out[] = $in;
                    $m_bytes = 1;
                }
                elseif (0xC0 == (0xE0 & $in))
                {
                    // First octet of 2 octet sequence
                    $m_ucs4 = $in;
                    $m_ucs4 = ($m_ucs4 & 0x1F) << 6;
                    $m_state = 1;
                    $m_bytes = 2;
                }
                elseif (0xE0 == (0xF0 & $in))
                {
                    // First octet of 3 octet sequence
                    $m_ucs4 = $in;
                    $m_ucs4 = ($m_ucs4 & 0x0F) << 12;
                    $m_state = 2;
                    $m_bytes = 3;
                }
                elseif (0xF0 == (0xF8 & $in))
                {
                    // First octet of 4 octet sequence
                    $m_ucs4 = $in;
                    $m_ucs4 = ($m_ucs4 & 0x07) << 18;
                    $m_state = 3;
                    $m_bytes = 4;
                }
                elseif (0xF8 == (0xFC & $in))
                {
                    /** First octet of 5 octet sequence.
                     *
                     * This is illegal because the encoded codepoint must be either
                     * (a) not the shortest form or
                     * (b) outside the Unicode range of 0-0x10FFFF.
                     * Rather than trying to resynchronize, we will carry on until the end
                     * of the sequence and let the later error handling code catch it.
                     **/
                    $m_ucs4 = $in;
                    $m_ucs4 = ($m_ucs4 & 0x03) << 24;
                    $m_state = 4;
                    $m_bytes = 5;
                }
                elseif (0xFC == (0xFE & $in))
                {
                    // First octet of 6 octet sequence, see comments for 5 octet sequence.
                    $m_ucs4 = $in;
                    $m_ucs4 = ($m_ucs4 & 1) << 30;
                    $m_state = 5;
                    $m_bytes = 6;
                }
                else
                {
                    global $logger;

                    // Current octet is neither in the US-ASCII range nor a legal first octet of a multi-octet sequence.
                    $logger->debug('Utf8->toUnicode: Illegal sequence identifier in UTF-8 at byte '.$i. 'in this string: '.$str);

                    return false;
                }
            }
            else
            {
                // When m_state is non-zero, we expect a continuation of the multi-octet sequence
                if (0x80 == (0xC0 & $in))
                {
                    // Legal continuation
                    $shift = ($m_state - 1) * 6;
                    $tmp = $in;
                    $tmp = ($tmp & 0x0000003F) << $shift;
                    $m_ucs4 |= $tmp;

                    // End of the multi-octet sequence. mUcs4 now contains the final Unicode codepoint to be output
                    if (0 == --$m_state)
                    {
                        // Check for illegal sequences and codepoints

                        // From Unicode 3.1, non-shortest form is illegal
                        if (((2 == $m_bytes) AND ($m_ucs4 < 0x0080)) OR
                                ((3 == $m_bytes) AND ($m_ucs4 < 0x0800)) OR
                                ((4 == $m_bytes) AND ($m_ucs4 < 0x10000)) OR
                                (4 < $m_bytes) OR
                                // From Unicode 3.2, surrogate characters are illegal
                                (($m_ucs4 & 0xFFFFF800) == 0xD800) OR
                                // Codepoints outside the Unicode range are illegal
                                ($m_ucs4 > 0x10FFFF))
                        {
                            global $logger;

                            $logger->debug('Utf8->toUnicode: Illegal sequence or codepoint in UTF-8 at byte '.$i.' in this string: '.$str);

                            return false;
                        }

                        if (0xFEFF != $m_ucs4)
                        {
                            $out[] = $m_ucs4;  // BOM is legal but we don't want to output it
                        }

                        // Initialize UTF-8 cache
                        $m_state = 0;
                        $m_ucs4  = 0;
                        $m_bytes = 1;
                    }
                }
                else
                {
                    global $logger;

                    // ((0xC0 & (*in) != 0x80) AND (m_state != 0))
                    // Incomplete multi-octet sequence
                    
                    $logger->debug('Utf8->toUnicode: Incomplete multi-octet sequence in UTF-8 at byte '.$i. 'in this string: '.$str);

                    return false;
                }
            }
        }

        return $out;
    }
}

/* End of file tounicode.php */
/* Location: ./packages/utf8/releases/0.0.1/src/tounicode.php */