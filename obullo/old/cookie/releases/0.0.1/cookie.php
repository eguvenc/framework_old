<?php

/**
* Cookie Class
*
* @package       packages
* @subpackage    cookie
* @category      cookies
* @link
*/

Class Cookie {
    
    public function __construct()
    {
        global $logger;

        if( ! isset(getInstance()->cookie))
        {
            getInstance()->cookie = $this; // Make available it in the controller $this->cookie->method();
        }

        $logger->debug('Cookie Class Initialized');
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
    public function set($name = '', $value = '', $expire = 0, $domain = '', $path = '/', $prefix = '', $secure = false)
    {
        global $config;

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

        if ($prefix == '' AND $config['cookie_prefix'] != '')
        {
            $prefix = $config['cookie_prefix'];
        }
        
        if ($domain == '' AND $config['cookie_domain'] != '')
        {
            $domain = $config['cookie_domain'];
        }
        
        if ($path   == '/' AND $config['cookie_path'] != '/')
        {
            $path   = $config['cookie_path'];
        }
        
        if ($secure == false AND $config['cookie_secure'] != false)
        {
            $secure = $config['cookie_secure'];
        }

        if ($expire == '' AND $config['cookie_expire'] != '')
        {
            $expire = $config['cookie_expire'];
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
    public function get($index = '', $xss_clean = false)
    {
        global $config;

        $prefix = '';
        if ( ! isset($_COOKIE[$index]) AND $config['cookie_prefix'] != '')
        {
            $prefix = $config['cookie_prefix'];
        }

        return Get::fetchFromArray($_COOKIE, $prefix.$index, $xss_clean);
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
    public function delete($name = '', $domain = '', $path = '/', $prefix = '')
    {
        $this->set($name, '', '', $domain, $path, $prefix);
    }   
    
}

/* End of file cookie.php */
/* Location: ./packages/cookie/releases/0.0.1/cookie.php */