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
* UTF8 trim
*
* @access  private
* @param   string $str
* @param   string $charlist
* @return  string
*/
if( ! function_exists('utf8_trim'))
{
    function utf8_trim($str, $charlist = NULL)
    {
        if ($charlist === NULL)
        {
            return trim($str);
        }

        $utf8 = lib('ob/utf8');
        
        return $utf8->ltrim($utf8->rtrim($str, $charlist), $charlist);
    }
}

/* End of file trim.php */
/* Location: ./obullo/helpers/drivers/utf8/trim.php */