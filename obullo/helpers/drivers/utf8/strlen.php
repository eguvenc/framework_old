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
* UTF8 strlen
*
* @access  private
* @param   string $str
* @return  string
*/
if( ! function_exists('utf8_strlen'))
{
    function utf8_strlen($str)
    {
        $utf8 = lib('ob/utf8');

        if($utf8->is_ascii($str))
        {
            return strlen($str);
        }

        return strlen(utf8_decode($str));
    }
}

/* End of file strlen.php */
/* Location: ./obullo/helpers/drivers/utf8/strlen.php */