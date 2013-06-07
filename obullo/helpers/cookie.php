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
 * Obullo Cookie Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Obullo Team
 * @link        
 */

// ------------------------------------------------------------------------
/**
* Set cookie
*
* Accepts six parameter, or you can submit an associative
* array in the first parameter containing all the values.
*
* @access   public
* @param    mixed
* @param    string    the value of the cookie
* @param    string    the number of seconds until expiration
* @param    string    the cookie domain.  Usually:  .yourdomain.com
* @param    string    the cookie path
* @param    string    the cookie prefix
* @return   void
*/
if( ! function_exists('set_cookie') ) 
{
    function set_cookie($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = FALSE)
    {
        if (is_array($name))
        {        
            foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'name') as $item)
            {
                if (isset($name[$item]))
                {
                    $$item = $name[$item];
                }
            }
        }

        $config = lib('ob/Config');   // Set the config file options

        if ($prefix == '' AND $config->item('cookie_prefix') != '')
        {
            $prefix = $config->item('cookie_prefix');
        }
        
        if ($domain == '' AND $config->item('cookie_domain') != '')
        {
            $domain = $config->item('cookie_domain');
        }
        
        if ($path   == '/' AND $config->item('cookie_path') != '/')
        {
            $path   = $config->item('cookie_path');
        }
        
        if ($secure == FALSE AND $config->item('cookie_secure') != FALSE)
        {
            $secure = $config->item('cookie_secure');
        }
        
        if ( ! is_numeric($expire))
        {
            $expire = time() - 86500;
        }
        else
        {
            if ($expire > 0)
            {
                $expire = time() + $expire;
            }
            else
            {
                $expire = 0;
            }
        }

        setcookie($prefix.$name, $value, $expire, $path, $domain, $secure);
    }
}
    
// --------------------------------------------------------------------

/**
* Fetch an item from the COOKIE array
*
* @access   public
* @param    string
* @param    bool
* @return   mixed
*/
if( ! function_exists('get_cookie') ) 
{
    function get_cookie($index = '', $xss_clean = FALSE)
    {
        $config = lib('ob/Config');
        
        $prefix = '';
        
        if ( ! isset($_COOKIE[$index]) && $config->item('cookie_prefix') != '')
        {
            $prefix = $config->item('cookie_prefix');
        }
        
        return i_cookie($prefix.$index, $xss_clean);
    }
}

// --------------------------------------------------------------------

/**
* Delete a COOKIE
*
* @param    mixed
* @param    string    the cookie domain.  Usually:  .yourdomain.com
* @param    string    the cookie path
* @param    string    the cookie prefix
* @return   void
*/
if( ! function_exists('delete_cookie') ) 
{
    function delete_cookie($name = '', $domain = '', $path = '/', $prefix = '')
    {
        set_cookie($name, '', '', $domain, $path, $prefix);
    }
}

/* End of file cookie.php */
/* Location: ./obullo/helpers/cookie.php */