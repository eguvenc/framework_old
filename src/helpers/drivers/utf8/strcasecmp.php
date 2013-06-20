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
 * UTF8 str_strcasecmp
 *
 * @access  private
 * @param   string $str1
 * @param   string $str2
 * @return string
 */
if( ! function_exists('utf8_strcasecmp'))
{
    function utf8_strcasecmp($str1, $str2)
    {
        $utf8 = lib('ob/utf8');
        
        if ($utf8->is_ascii($str1) AND $utf8->is_ascii($str2))
        {
            return strcasecmp($str1, $str2);
        }

        $str1 = $utf8->strtolower($str1);
        $str2 = $utf8->strtolower($str2);
        
        return strcmp($str1, $str2);
    }
}

/* End of file str_casecmp.php */
/* Location: ./obullo/helpers/drivers/utf8/str_casecmp.php */