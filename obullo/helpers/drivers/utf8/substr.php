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
* UTF8 substr
*
* @access  private
* @param   string $str
* @param   string $offset
* @param   string $length
* @return  string
*/
if( ! function_exists('utf8_substr'))
{
    function utf8_substr($str, $offset, $length = NULL)
    {
        $utf8 = lib('ob/utf8');
        
        if($utf8->is_ascii($str))
        {
            return ($length === NULL) ? substr($str, $offset) : substr($str, $offset, $length);
        }
                    
        $str    = (string) $str;          // Normalize params
        $strlen = $utf8->strlen($str);
        $offset = (int) ($offset < 0) ? max(0, $strlen + $offset) : $offset; // Normalize to positive offset
        $length = ($length === NULL) ? NULL : (int) $length;

        if($length === 0 OR $offset >= $strlen OR ($length < 0 AND $length <= $offset - $strlen))  // Impossible
        {
            return '';
        }
                
        if($offset == 0 AND ($length === NULL OR $length >= $strlen))         // Whole string
        {
            return $str;
        }
                
        $regex = '^';         // Build regex

        if ($offset > 0)      // Create an offset expression
        {
            // PCRE repeating quantifiers must be less than 65536, so repeat when necessary
            $x = (int) ($offset / 65535);
            $y = (int) ($offset % 65535);
            $regex .= ($x == 0) ? '' : ('(?:.{65535}){'.$x.'}');
            $regex .= ($y == 0) ? '' : ('.{'.$y.'}');
        }

        if ($length === NULL)         // Create a length expression
        {
            $regex .= '(.*)'; // No length set, grab it all
        }
        elseif ($length > 0)         // Find length from the left (positive length)
        {   
            $length = min($strlen - $offset, $length);  // Reduce length so that it can't go beyond the end of the string

            $x = (int) ($length / 65535);
            $y = (int) ($length % 65535);
            $regex .= '(';
            $regex .= ($x == 0) ? '' : ('(?:.{65535}){'.$x.'}');
            $regex .= '.{'.$y.'})';
        }
        else            // Find length from the right (negative length)
        {
            $x = (int) (-$length / 65535);
            $y = (int) (-$length % 65535);
            $regex .= '(.*)';
            $regex .= ($x == 0) ? '' : ('(?:.{65535}){'.$x.'}');
            $regex .= '.{'.$y.'}';
        }

        preg_match('/'.$regex.'/us', $str, $matches);
        
        return $matches[1];
    }

}

/* End of file substr.php */
/* Location: ./obullo/helpers/drivers/utf8/substr.php */