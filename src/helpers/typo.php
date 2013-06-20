<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @license         public
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Typography Helpers

 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Guvenc
 * @link        
 */

// ------------------------------------------------------------------------

/**
* Convert newlines to HTML line breaks except within PRE tags
*
* @access	public
* @param	string
* @return	string
*/
if( ! function_exists('nl2br_except_pre') ) 
{
    function nl2br_except_pre($str)
    {
        return lib('ob/Typo')->nl2br_except_pre($str);
    }
}
// ------------------------------------------------------------------------

/**
* Auto Typography Wrapper Function
*
*
* @access	public
* @param	string
* @param	bool	whether to reduce multiple instances of double newlines to two
* @return	string
*/
if( ! function_exists('auto_typo') ) 
{
    function auto_typo($str, $reduce_linebreaks = FALSE)
    {
        return lib('ob/Typo')->auto_typo($str, $reduce_linebreaks);
    }
}
    
/* End of file typography.php */
/* Location: ./obullo/helpers/typography.php */