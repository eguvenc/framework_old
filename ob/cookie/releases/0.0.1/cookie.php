<?php 

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

        if ($prefix == '' AND config('cookie_prefix') != '')
        {
            $prefix = config('cookie_prefix');
        }
        
        if ($domain == '' AND config('cookie_domain') != '')
        {
            $domain = config('cookie_domain');
        }
        
        if ($path   == '/' AND config('cookie_path') != '/')
        {
            $path   = config('cookie_path');
        }
        
        if ($secure == FALSE AND config('cookie_secure') != FALSE)
        {
            $secure = config('cookie_secure');
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
        $prefix = '';
        
        if ( ! isset($_COOKIE[$index]) AND config('cookie_prefix') != '')
        {
            $prefix = config('cookie_prefix');
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
/* Location: ./ob/cookie/releases/0.0.1/cookie.php */