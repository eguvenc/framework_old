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
* UTF8 ucfirst
*
* @access  private
* @param   string $str
* @return  string
*/
if( ! function_exists('utf8_ucfirst'))
{
    function utf8_ucfirst($str)
    {
        $utf8 = lib('ob/utf8');

        if($utf8->is_ascii($str))
        {
            return ucfirst($str);
        }

        preg_match('/^(.?)(.*)$/us', $str, $matches);
        
	return $utf8->strtoupper($matches[1]).$matches[2];
    }
}

/* End of file ucfirst.php */
/* Location: ./obullo/helpers/drivers/utf8/ucfirst.php */