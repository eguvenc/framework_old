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
* UTF8 strpos
*
* @access  private
* @param   string $str
* @param   int $offset
* @return  string
*/
if( ! function_exists('utf8_strpos'))
{
    function utf8_strpos($str, $search, $offset = 0)
    {
        $offset = (int) $offset;

        $utf8 = lib('ob/utf8');

        if ($utf8->is_ascii($str) AND $utf8->is_ascii($search))
        {
            return strpos($str, $search, $offset);
        }

        if ($offset == 0)
        {
            $array = explode($search, $str, 2);

            return isset($array[1]) ? $utf8->strlen($array[0]) : FALSE;
        }

        $str = $utf8->substr($str, $offset);
        $pos = $utf8->strpos($str, $search);
        
        return ($pos === FALSE) ? FALSE : ($pos + $offset);
    }
}

/* End of file strpos.php */
/* Location: ./obullo/helpers/drivers/utf8/strpos.php */