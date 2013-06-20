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
 * UTF8 rtrim
 *
 * @access  private
 * @param type $str
 * @param type $charlist
 */
if(function_exists('utf8_rtrim'))
{
    function utf8_rtrim($str, $charlist = NULL)
    {
        if ($charlist === NULL)
        {
            return rtrim($str);
        }	

        if (lib('ob/utf8')->is_ascii($charlist))
        {
            return rtrim($str, $charlist);   
        }

        $charlist = preg_replace('#[-\[\]:\\\\^/]#', '\\\\$0', $charlist);

        return preg_replace('/['.$charlist.']++$/uD', '', $str);
    }
}

/* End of file rtrim.php */
/* Location: ./obullo/helpers/drivers/utf8/rtrim.php */