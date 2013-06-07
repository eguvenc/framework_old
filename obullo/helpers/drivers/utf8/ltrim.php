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
 * UTF8 ltrim
 * Trim left space with utf8 support
 *
 * @access  public
 * @param string $str
 * @param string $charlist
 * @return string
 */
if( ! function_exists('utf8_ltrim'))
{
    function utf8_ltrim($str, $charlist = NULL)
    {
        if ($charlist === NULL)
        {
            return ltrim($str);
        }

        if (lib('ob/utf8')->is_ascii($charlist))
        {
            return ltrim($str, $charlist);
        }

        $charlist = preg_replace('#[-\[\]:\\\\^/]#', '\\\\$0', $charlist);

        return preg_replace('/^['.$charlist.']+/u', '', $str);
    }
}

/* End of file ltrim.php */
/* Location: ./obullo/helpers/drivers/utf8/ltrim.php */