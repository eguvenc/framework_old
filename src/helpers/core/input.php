<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo
 * @author          obullo.com
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Input Helper ( Friendly Input Functions )
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Guvenc
 * @link        
 */

// -------------------------------------------------------------------- 

/**
* Fetch an item from the GET array
*
* @access   public
* @param    string
* @param    bool
* @param    bool    Use global get values instead of HMVC values.
* @return   string
*/
if( ! function_exists('i_get') )
{
    function i_get($index = '', $xss_clean = FALSE, $use_global_var = FALSE)
    {
        $GET = ($use_global_var) ? $GLOBALS['_GET_BACKUP']: $_GET; // _GET_BACKUP = Hmvc local get values
        
        return lib('ob/Input')->_fetch_from_array($GET, $index, $xss_clean);
    }
}
// --------------------------------------------------------------------

/**
* Fetch an item from the POST array
*
* @access   public
* @param    string
* @param    bool
* @param    bool    Use global post values instead of HMVC values.
* @return   string
*/
if( ! function_exists('i_post') )
{
    function i_post($index = '', $xss_clean = FALSE, $use_global_var = FALSE)
    {
        $POST = ($use_global_var) ? $GLOBALS['_POST_BACKUP']: $_POST; // _POST_BACKUP = Hmvc local post values

        return lib('ob/Input')->_fetch_from_array($POST, $index, $xss_clean);
    }
}

// --------------------------------------------------------------------

/**
* Fetch an item from the REQUEST array
*
* @access   public
* @param    string
* @param    bool
* @param    bool    Use global request values instead of HMVC values.
* @return   string
*/
if( ! function_exists('i_request') )
{
    function i_request($index = '', $xss_clean = FALSE, $use_global_var = FALSE)
    {
        $REQUEST = ($use_global_var) ? $GLOBALS['_REQUEST_BACKUP']: $_REQUEST; // _REQUEST_BACKUP = Hmvc local request values

        return lib('ob/Input')->_fetch_from_array($REQUEST, $index, $xss_clean);
    }
}

// --------------------------------------------------------------------

/**
* Fetch an item from either the GET array or the POST
*
* @access   public
* @param    string  The index key
* @param    bool    XSS cleaning
 *@param    bool    Use global post values instead of HMVC values.
* @return   string
*/
if( ! function_exists('i_get_post') )
{
    function i_get_post($index = '', $xss_clean = FALSE, $use_global_var = FALSE)
    {
        $POST = ($use_global_var) ? $GLOBALS['_POST_BACKUP'] : $_POST; //  _POST_BACKUP = Hmvc local post values.

        if ( ! isset($POST[$index]) )
        {
            return i_get($index, $xss_clean, $use_global_var);
        }
        else
        {
            return i_post($index, $xss_clean, $use_global_var);
        }
    }
}
// --------------------------------------------------------------------

/**
* Fetch an item from the COOKIE array
*
* @access   public
* @param    string
* @param    bool
* @return   string
*/
if( ! function_exists('i_cookie') )
{
    function i_cookie($index = '', $xss_clean = FALSE)
    {
        return lib('ob/Input')->_fetch_from_array($_COOKIE, $index, $xss_clean);
    }
}
// --------------------------------------------------------------------

/**
* Fetch an item from the SERVER array
* WE DON'T need to $use_global_var variable because of
* we already use global $_SERVER values in HMVC requests
* except the http method variable.
*
* @access   public
* @param    string
* @param    bool
* @return   string
*/
if( ! function_exists('i_server') )
{
    function i_server($index = '', $xss_clean = FALSE)
    {
        return lib('ob/Input')->_fetch_from_array($_SERVER, $index, $xss_clean);
    }
}
// --------------------------------------------------------------------

/**
* Fetch the IP Address
*
* @access    public
* @return    string
*/
if( ! function_exists('i_ip_address') )
{
    function i_ip_address()
    {
        return lib('ob/Input')->ip_address();
    }
}
// --------------------------------------------------------------------

/**
* Validate IP Address
*
* Updated version suggested by Geert De Deckere
*
* @access   public
* @param    string
* @return   string
*/
if( ! function_exists('i_valid_ip') )
{
    function i_valid_ip($ip)
    {        
        return lib('ob/Input')->valid_ip($ip);
    }
}
// --------------------------------------------------------------------

/**
* User Agent
*
* @access    public
* @return    string
*/
if( ! function_exists('i_user_agent') )
{
    function i_user_agent()
    {
        $input = lib('ob/Input');

        if ($input->user_agent !== FALSE)
        {
            return $input->user_agent;
        }

        $input->user_agent = ( ! isset($_SERVER['HTTP_USER_AGENT'])) ? FALSE : $_SERVER['HTTP_USER_AGENT'];

        return $input->user_agent;
    }
}
// --------------------------------------------------------------------

/**
* Filename Security
*
* @access   public
* @param    string
* @return   string
*/
if( ! function_exists('i_filename_security') )
{
    function i_filename_security($str)
    {
        $bad = array(
                        "../",
                        "./",
                        "<!--",
                        "-->",
                        "<",
                        ">",
                        "'",
                        '"',
                        '&',
                        '$',
                        '#',
                        '{',
                        '}',
                        '[',
                        ']',
                        '=',
                        ';',
                        '?',
                        "%20",
                        "%22",
                        "%3c",        // <
                        "%253c",     // <
                        "%3e",         // >
                        "%0e",         // >
                        "%28",         // (
                        "%29",         // )
                        "%2528",     // (
                        "%26",         // &
                        "%24",         // $
                        "%3f",         // ?
                        "%3b",         // ;
                        "%3d"        // =
                    );

        return stripslashes(str_replace($bad, '', $str));
    }
}
// --------------------------------------------------------------------

/**
* Check Request Is Ajax.
* 
* Test to see if a request contains the HTTP_X_REQUESTED_WITH header
* 
* @return boolean
*/
if( ! function_exists('i_ajax'))
{
    function i_ajax()
    {    
        return (i_server('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest');
    } 
}
// --------------------------------------------------------------------

/**
* Check is Request Command Line.
* 
* @return boolean
*/
if( ! function_exists('i_cli'))
{
    function i_cli()
    {    
        if(defined('TASK'))
        {
            return FALSE;
        }
        
        if(defined('CMD'))
        {
            return TRUE;
        }
        
        return FALSE;
    } 
}
// --------------------------------------------------------------------

/**
* Check is Request Task.
* 
* @return boolean
*/
if( ! function_exists('i_task'))
{
    function i_task()
    {    
        if(defined('TASK'))
        {
            return TRUE;
        }
        
        return FALSE;
    } 
}
// --------------------------------------------------------------------

/**
* Check is Request HMVC.
* 
* @return boolean
*/
if( ! function_exists('i_hmvc'))
{
    function i_hmvc()
    {    
        if(lib('ob/Router')->is_hmvc())
        {
            return TRUE;
        }
        
        return FALSE;
    } 
}

/* End of file input.php */
/* Location: ./obullo/helpers/core/input.php */