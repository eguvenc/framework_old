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
 * UTF8 str_split
 *
 * @access  private
 * @param string $str
 * @param int $split_length
 * @return string
 */
if( ! function_exists('utf8_str_split'))
{
    function utf8_str_split($str, $split_length = 1)
    {
        $utf8 = lib('ob/utf8');
        
        $split_length = (int) $split_length;

        if($utf8->is_ascii($str))
        {
            return str_split($str, $split_length);
        }

        if($split_length < 1)
        {
            return FALSE;
        }

        if($utf8->strlen($str) <= $split_length)
        {
           return array($str);
        }
                
        preg_match_all('/.{'.$split_length.'}|[^\x00]{1,'.$split_length.'}$/us', $str, $matches);

        return $matches[0];
    }
}

/* End of file str_split.php */
/* Location: ./obullo/helpers/drivers/utf8/str_split.php */