<?php

/**
 * Obullo Input Class
 *
 * @package     Obullo
 * @subpackage  Libraries
 * @category    Language
 * @author      Obullo Team
 * @link        
 */

Class Input {
        
    public static $instance;
    
    public $ip_address            = FALSE;
    public $user_agent            = FALSE;
    public $_allow_get_array      = FALSE;
    public $_standardize_newlines = TRUE;
    public $enable_csrf           = FALSE;
    public $enable_xss            = FALSE;
    
    /**
    * Constructor
    */
    public function __construct()
    {
        $this->_allow_get_array = (config('enable_query_strings') === TRUE) ? TRUE : FALSE;
        $this->enable_xss       = (config('global_xss_filtering') === TRUE) ? TRUE : FALSE;
        $this->enable_csrf      = (config('csrf_protection') === TRUE) ? TRUE : FALSE;

        log_me('debug', "Input Class Initialized");
    }
    
    // --------------------------------------------------------------------

    public static function getInstance()
    {
       if( ! self::$instance instanceof self)
       {
           self::$instance = new self();
       } 
       
       return self::$instance;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Sanitize Globals
    * This function does the following:
    * Unsets $_GET data (if query strings are not enabled)
    * Unsets all globals if register_globals is enabled.
    *
    * Standardizes newline characters to \n
    *
    * @access    private
    * @return    void
    */
    public function _sanitize_globals()
    {
        // Would kind of be "wrong" to unset any of these GLOBALS
        $protected = array('_SERVER', '_GET', '_POST', '_FILES', '_REQUEST', '_SESSION', '_ENV', '_controller',
        'GLOBALS', 'HTTP_RAW_POST_DATA');

        // Unset globals for security.
        // This is effectively the same as register_globals = off
        foreach (array($_GET, $_POST, $_COOKIE, $_SERVER, $_FILES, $_ENV, (isset($_SESSION) && is_array($_SESSION)) ? $_SESSION : array()) as $global)
        {
            if ( ! is_array($global))
            {
                if ( ! in_array($global, $protected))
                {
                    unset($GLOBALS[$global]);
                }
            }
            else
            {
                foreach ($global as $key => $val)
                {
                    if ( ! in_array($key, $protected))
                    {
                        unset($GLOBALS[$key]);
                    }

                    if (is_array($val))
                    {
                        foreach($val as $k => $v)
                        {
                            if ( ! in_array($k, $protected))
                            {
                                unset($GLOBALS[$k]);
                            }
                        }
                    }
                }
            }
        }

        // Is $_GET data allowed? If not we'll set the $_GET to an empty array
        if ($this->_allow_get_array == FALSE)
        {
            $_GET = array();
        }
        else
        {
            $_GET = $this->_clean_input_data($_GET);
        }

        // Clean $_POST Data
        $_POST = $this->_clean_input_data($_POST);

        // Sanitize PHP_SELF
        $_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']);

        // CSRF Protection check
        if ($this->enable_csrf == TRUE)
        {
            Security::getInstance()->csrf_verify();
        }
        
        // Clean $_COOKIE Data
        // Also get rid of specially treated cookies that might be set by a server
        // or silly application, that are of no use to a OB application anyway
        // but that when present will trip our 'Disallowed Key Characters' alarm
        // http://www.ietf.org/rfc/rfc2109.txt
        // note that the key names below are single quoted strings, and are not PHP variables
        unset($_COOKIE['$Version']);
        unset($_COOKIE['$Path']);
        unset($_COOKIE['$Domain']);
        
        $_COOKIE = $this->_clean_input_data($_COOKIE);

        log_me('debug', "Global POST and COOKIE data sanitized");
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Fetch the IP Address
    *
    * @access    public
    * @return    string
    */
    public function ip_address()
    {
        if ($this->ip_address !== FALSE)
        {
            return $this->ip_address;
        }

        if (config('proxy_ips') != '' && i_server('HTTP_X_FORWARDED_FOR') && i_server('REMOTE_ADDR'))
        {
            $proxies = preg_split('/[\s,]/', config('proxy_ips'), -1, PREG_SPLIT_NO_EMPTY);
            $proxies = is_array($proxies) ? $proxies : array($proxies);

            $this->ip_address = in_array($_SERVER['REMOTE_ADDR'], $proxies) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        }
        elseif (i_server('REMOTE_ADDR') AND i_server('HTTP_CLIENT_IP'))
        {
            $this->ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (i_server('REMOTE_ADDR'))
        {
            $this->ip_address = $_SERVER['REMOTE_ADDR'];
        }
        elseif (i_server('HTTP_CLIENT_IP'))
        {
            $this->ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (i_server('HTTP_X_FORWARDED_FOR'))
        {
            $this->ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if ($this->ip_address === FALSE)
        {
            $this->ip_address = '0.0.0.0';
            
            return $this->ip_address;
        }

        if (strstr($this->ip_address, ','))
        {
            $x = explode(',', $this->ip_address);
            
            $this->ip_address = trim(end($x));
        }

        if ( ! i_valid_ip($this->ip_address))
        {
            $this->ip_address = '0.0.0.0';
        }

        return $this->ip_address;
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Validate IP Address
    *
    * Updated version suggested by Geert De Deckere
    *
    * @access   public
    * @param    string
    * @return   string
    */
    public function valid_ip($ip)
    {
        $ip_segments = explode('.', $ip);

        // Always 4 segments needed
        if (count($ip_segments) != 4)
        {
            return FALSE;
        }
        // IP can not start with 0
        if ($ip_segments[0][0] == '0')
        {
            return FALSE;
        }
        // Check each segment
        foreach ($ip_segments as $segment)
        {
            // IP segments must be digits and can not be
            // longer than 3 digits or greater then 255
            if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3)
            {
                return FALSE;
            }
        }

        return TRUE;
    }
   
    // ------------------------------------------------------------------------

    /**
    * Clean Input Data
    *
    * This is a helper function. It escapes data and
    * standardizes newline characters to \n
    *
    * @access   private
    * @param    string
    * @return   string
    */
    public function _clean_input_data($str)
    {
        if (is_array($str))
        {
            $new_array = array();
            foreach ($str as $key => $val)
            {
                $new_array[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
            }
            
            return $new_array;
        }

        // We strip slashes if magic quotes is on to keep things consistent
        if (function_exists('get_magic_quotes_gpc') AND get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }  

        // Remove control characters
	$str = remove_invisible_characters($str);
        
        // Should we filter the input data?
        if ($this->enable_xss === TRUE)
        {
            $str = Security::getInstance()->xss_clean($str);
        }

        // Standardize newlines if needed
        if ($this->_standardize_newlines == TRUE)
        {
            if (strpos($str, "\r") !== FALSE)
            {
                $str = str_replace(array("\r\n", "\r", "\r\n\n"), PHP_EOL, $str);
            }
        }
        
        return $str;
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Clean Keys
    *
    * This is a helper function. To prevent malicious users
    * from trying to exploit keys we make sure that keys are
    * only named with alpha-numeric text and a few other items.
    *
    * @access   private
    * @param    string
    * @return   string
    */
    public function _clean_input_keys($str)
    {
        if ( ! preg_match("/^[a-z0-9:_\/-]+$/i", $str))
        {
            exit('Disallowed Key Characters.');
        }

        return $str;
    }
    
    // --------------------------------------------------------------------

    /**
    * Fetch from array
    *
    * This is a helper function to retrieve values from global arrays
    *
    * @access   public
    * @param    array
    * @param    string
    * @param    bool
    * @return   string
    */
    public function _fetch_from_array(&$array, $index = '', $xss_clean = FALSE)
    {
        if ( ! isset($array[$index]))
        {
            return FALSE;
        }

        if ($xss_clean === TRUE)
        {
            return Security::getInstance()->xss_clean($array[$index]);
        }

        return $array[$index];
    }
   
}

// END Input Class
// -------------------------------------------------------------------- 

// Helper Functions for Input Class.

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
        
        return getInstance()->input->_fetch_from_array($GET, $index, $xss_clean);
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

        return getInstance()->input->_fetch_from_array($POST, $index, $xss_clean);
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

        return getInstance()->input->_fetch_from_array($REQUEST, $index, $xss_clean);
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
        return getInstance()->input->_fetch_from_array($_COOKIE, $index, $xss_clean);
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
        return getInstance()->input->_fetch_from_array($_SERVER, $index, $xss_clean);
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
        return getInstance()->input->ip_address();
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
        return getInstance()->input->valid_ip($ip);
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
        $input = getInstance()->input;

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
        
        if(defined('STDIN'))
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
        if(Router::getInstance()->is_hmvc())
        {
            return TRUE;
        }
        
        return FALSE;
    } 
}

/* End of file input.php */
/* Location: ./ob_modules/input/releases/0.0.1/input.php */