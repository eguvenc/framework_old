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
* UTF8 stristr
*
* @access  private
* @param   string $str
* @param   string $search
* @return  string
*/
if( ! function_exists('utf8_stristr'))
{
    function utf8_stristr($str, $search)
    {
        $utf8 = lib('ob/utf8');
        
        if($utf8->is_ascii($str) AND $utf8->is_ascii($search))
        {
            return stristr($str, $search);
        }
                
        if($search == '')
        {
            return $str;
        }

        $str_lower    = $utf8->strtolower($str);
        $search_lower = $utf8->strtolower($search);

        preg_match('/^(.*?)'.preg_quote($search_lower, '/').'/s', $str_lower, $matches);

        if(isset($matches[1]))
        {
            return substr($str, strlen($matches[1]));
        } 

        return FALSE;
    }
}


/* End of file stristr.php */
/* Location: ./obullo/helpers/drivers/utf8/stristr.php */