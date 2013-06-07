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
* UTF8 ucwords
*
* @access  private
* @param   string $str
* @return  string
*/
if( ! function_exists('utf8_ucwords'))
{
    function utf8_ucwords($str)
    {
        $utf8 = lib('ob/utf8');

        if($utf8->is_ascii($str))
        {
            return ucwords($str);
        }

        // [\x0c\x09\x0b\x0a\x0d\x20] matches form feeds, horizontal tabs, vertical tabs, linefeeds and carriage returns.
        // This corresponds to the definition of a 'word' defined at http://php.net/ucwords
        return preg_replace(
            '/(?<=^|[\x0c\x09\x0b\x0a\x0d\x20])[^\x0c\x09\x0b\x0a\x0d\x20]/ue',
            '$utf8->strtoupper(\'$0\')',
            $str
        );
    }
}

/* End of file ucwords.php */
/* Location: ./obullo/helpers/drivers/utf8/ucwords.php */