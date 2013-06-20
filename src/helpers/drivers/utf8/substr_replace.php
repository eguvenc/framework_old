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
* UTF8 substr_replace
*
* @access  private
* @param   string $str
* @param   string $replacement
* @param   int $offset
* @param   int $length
* @return  string
*/
if( ! function_exists('utf8_substr_replace'))
{
    function utf8_substr_replace($str, $replacement, $offset, $length = NULL)
    {
        $utf8 = lib('ob/utf8');
        
        if($utf8->is_ascii($str))
        {
            return ($length === NULL) ? substr_replace($str, $replacement, $offset) : substr_replace($str, $replacement, $offset, $length);
        }
                
        $length = ($length === NULL) ? $utf8->strlen($str) : (int) $length;
        
        preg_match_all('/./us', $str, $str_array);
        preg_match_all('/./us', $replacement, $replacement_array);

        array_splice($str_array[0], $offset, $length, $replacement_array[0]);
        
        return implode('', $str_array[0]);
    }
}

/* End of file substr_replace.php */
/* Location: ./obullo/helpers/drivers/utf8/substr_replace.php */