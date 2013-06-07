<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo    
 * @author          obullo.com
 * @since           Version 1.0.1
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * UTF8 ord
 *
 * @access  private
 * @param string $str
 */
if( ! function_exists('utf8_ord'))
{
    function utf8_ord($chr)
    {
        $ord0 = ord($chr);

        if ($ord0 >= 0 AND $ord0 <= 127)
        {
            return $ord0;
        }

        if ( ! isset($chr[1]))
        {
            log_me('debug', 'Short sequence - at least 2 bytes expected, only 1 seen in this char: '. $chr);
            return FALSE;
        }

        $ord1 = ord($chr[1]);

        if ($ord0 >= 192 AND $ord0 <= 223)
        {
            return ($ord0 - 192) * 64 + ($ord1 - 128);
        }

        if ( ! isset($chr[2]))
        {
            log_me('debug', 'Short sequence - at least 3 bytes expected, only 2 seen in this char: '. $chr);
            return FALSE;
        }

        $ord2 = ord($chr[2]);

        if ($ord0 >= 224 AND $ord0 <= 239)
        {
            return ($ord0 - 224) * 4096 + ($ord1 - 128) * 64 + ($ord2 - 128);
        }    

        if ( ! isset($chr[3]))
        {
            log_me('debug', 'Short sequence - at least 4 bytes expected, only 3 seen in this char: '. $chr);
            return FALSE;
        }

        $ord3 = ord($chr[3]);

        if ($ord0 >= 240 AND $ord0 <= 247)
        {
            return ($ord0 - 240) * 262144 + ($ord1 - 128) * 4096 + ($ord2-128) * 64 + ($ord3 - 128);
        }

        if ( ! isset($chr[4]))
        {
            log_me('debug', 'Short sequence - at least 5 bytes expected, only 4 seen in this char: '. $chr);
            return FALSE;
        }

        $ord4 = ord($chr[4]);

        if ($ord0 >= 248 AND $ord0 <= 251)
        {
            return ($ord0 - 248) * 16777216 + ($ord1-128) * 262144 + ($ord2 - 128) * 4096 + ($ord3 - 128) * 64 + ($ord4 - 128);
        }

        if ( ! isset($chr[5]))
        {
            log_me('debug', 'Short sequence - at least 6 bytes expected, only 5 seen in this char: '. $chr);
            return FALSE;
        }

        if ($ord0 >= 252 AND $ord0 <= 253)
        {
            return ($ord0 - 252) * 1073741824 + ($ord1 - 128) * 16777216 + ($ord2 - 128) * 262144 + ($ord3 - 128) * 4096 + ($ord4 - 128) * 64 + (ord($chr[5]) - 128);
        }            

        if ($ord0 >= 254 AND $ord0 <= 255)
        {
            log_me('debug', 'Invalid UTF-8 with surrogate ordinal '.$ord0 . 'in this char: '. $chr);
            return FALSE;
        }
    }
    
}

/* End of file ord.php */
/* Location: ./obullo/helpers/drivers/utf8/ord.php */