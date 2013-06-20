<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @license         public
 * @since           Version 1.0.1
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * UTF8 from_unicode
 *
 * @access  private
 * @param  array $arr
 * @return string 
 */
if( ! function_exists('utf8_from_unicode')) 
{
    function utf8_from_unicode($arr)
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
                log_me('debug', 'UTF8 from_unicode: Illegal surrogate at index: '.$k.', value: '.$arr[$k]);
                return FALSE;
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
                log_me('debug', 'UTF8 from_unicode: Codepoint out of Unicode range at index: '.$k.', value: '.$arr[$k]);
                return FALSE;
            }
        }

        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }
    
}

/* End of file from_unicode.php */
/* Location: ./obullo/helpers/drivers/utf8/from_unicode.php */