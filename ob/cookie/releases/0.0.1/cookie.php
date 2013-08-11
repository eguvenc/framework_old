<?php
namespace Ob\cookie {
    
    /**
    * Cookie Helper
    *
    * @package     Obullo
    * @subpackage  Helpers
    * @category    Cookies
    * @link
    */
    Class start
    {
        function __construct()
        {
            \Ob\log\me('debug', 'Cookie Helper Initialized.');
        }
    }
    
    // --------------------------------------------------------------------
    
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
    function set($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = FALSE)
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

        if ($prefix == '' AND \Ob\config('cookie_prefix') != '')
        {
            $prefix = \Ob\config('cookie_prefix');
        }
        
        if ($domain == '' AND \Ob\config('cookie_domain') != '')
        {
            $domain = \Ob\config('cookie_domain');
        }
        
        if ($path   == '/' AND \Ob\config('cookie_path') != '/')
        {
            $path   = \Ob\config('cookie_path');
        }
        
        if ($secure == FALSE AND \Ob\config('cookie_secure') != FALSE)
        {
            $secure = \Ob\config('cookie_secure');
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
    
    // --------------------------------------------------------------------

    /**
    * Fetch an item from the COOKIE array
    *
    * @access   public
    * @param    string
    * @param    bool
    * @return   mixed
    */
    function get($index = '', $xss_clean = FALSE)
    {
        $prefix = '';
        
        if ( ! isset($_COOKIE[$index]) AND \Ob\config('cookie_prefix') != '')
        {
            $prefix = \Ob\config('cookie_prefix');
        }
        
        return \Ob\i\cookie($prefix.$index, $xss_clean);
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
    function delete($name = '', $domain = '', $path = '/', $prefix = '')
    {
        set($name, '', '', $domain, $path, $prefix);
    }   
    
}

/* End of file cookie.php */
/* Location: ./ob/cookie/releases/0.0.1/cookie.php */