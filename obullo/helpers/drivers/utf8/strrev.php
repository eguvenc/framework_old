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
* UTF8 strrev
*
* @access  private
* @param   string $str
* @return  string
*/
if( ! function_exists('utf8_strrev'))
{
    function utf8_strrev($str)
    {
        $utf8 = lib('ob/utf8');
        
        if($utf8->is_ascii($str))
        {
            return strrev($str);
        }

        preg_match_all('/./us', $str, $matches);
        
        return implode('', array_reverse($matches[0]));
    }
}

/* End of file strrev.php */
/* Location: ./obullo/helpers/drivers/utf8/strrev.php */