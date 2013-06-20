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
* UTF8 strrpos
*
* @access  private
* @param   string $str
* @param   string $search
* @param   int $offset
* @return  string
*/
if( ! function_exists('utf8_strrpos'))
{
    function utf8_strrpos($str, $search, $offset = 0)
    {
        $utf8 = lib('ob/utf8');
        
        $offset = (int) $offset;

        if($utf8->is_ascii($str) AND $utf8->is_ascii($search))
        {
            return strrpos($str, $search, $offset);
        }

        if ($offset == 0)
        {
            $array = explode($search, $str, -1);
            
            return isset($array[0]) ? $utf8->strlen(implode($search, $array)) : FALSE;
        }

        $str = $utf8->substr($str, $offset);
        $pos = $utf8->strrpos($str, $search);
        
        return ($pos === FALSE) ? FALSE : ($pos + $offset);
    }
}

/* End of file strrpos.php */
/* Location: ./obullo/helpers/drivers/utf8/strrpos.php */