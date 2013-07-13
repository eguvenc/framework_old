<?php
namespace Ob;

 /**
 * URI Class
 * Parses URIs and determines routing
 *
 * @package     Obullo
 * @subpackage  uri
 * @category    URI
 * @author      Obullo Team
 * @link
 */

Class Uri
{
    public $keyval      = array();
    public $uri_string;
    public $segments    = array();
    public $rsegments   = array();

    public $cache_time   = '';  // HMVC library just use this variable.
    public $extension    = '';
    public $uri_protocol = 'REQUEST_URI';
    
    public static $instance;

    /**
    * Constructor
    *
    * Simply globalizes the $RTR object.  The front
    * loads the Router class early on so it's not available
    * normally as other classes are.
    *
    * @access    public
    */
    public function __construct()
    {
        // Warning :
        // 
        // Don't load any library in __construct Level because of Obullo use 
        // the URI Class at Bootstrap. When you try loading any library
        // you will get a Fatal Error.
        
        log\me('debug', 'URI Class Initialized'); // core level log 
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

    public static function setInstance($object)
    {
        if(is_object($object))
        {
            self::$instance = $object;
        }
        
        return self::$instance;
    }
    
    /**
    * When we use HMVC we need to Clean
    * all data.
    *
    * @return  void
    */
    public function clear()
    {
        $this->keyval     = array();
        $this->uri_string = '';
        $this->segments   = array();
        $this->rsegments  = array();
        $this->cache_time = '';
        $this->extension  = 'php';
    }

    // --------------------------------------------------------------------

    /**
    * We use this function in HMVC library.
    *
    * @param mixed $uri
    */
    public function set_uri_string($uri = '', $filter = TRUE)
    {
        if($filter) // Filter out control characters
        {
            $uri = remove_invisible_characters($uri, FALSE);
        }
        
        $this->uri_string = $uri;
    }

    // --------------------------------------------------------------------

    /**
     * Get the URI String
     *
     * @access    private
     * @param     $hvmc  boolean
     * @return    string
     */
    public function _fetch_uri_string()
    {
        if($this->uri_string != '') 
        {
            return;
        }

        $protocol = strtoupper(config('uri_protocol'));
        
        if ($protocol == 'AUTO')
        {
            // If the URL has a question mark then it's simplest to just
            // build the URI string from the zero index of the $_GET array.
            // This avoids having to deal with $_SERVER variables, which
            // can be unreliable in some environments
            if (is_array($_GET) && count($_GET) == 1 && trim(key($_GET), '/') != '')
            {
                $this->uri_string = key($_GET);
                return;
            }

            // Is there a PATH_INFO variable?
            // Note: some servers seem to have trouble with getenv() so we'll test it two ways
            $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
            if (trim($path, '/') != '' && $path != "/".SELF)
            {
                $this->uri_protocol = 'PATH_INFO';
                $this->uri_string   = $path;
                return;
            }

            // No PATH_INFO?... What about QUERY_STRING?
            $path =  (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
            if (trim($path, '/') != '')
            {
                $this->uri_protocol = 'QUERY_STRING';
                $this->uri_string   = $path;
                return;
            }

            // No QUERY_STRING?... Maybe the ORIG_PATH_INFO variable exists?
            $path = (isset($_SERVER['ORIG_PATH_INFO'])) ? $_SERVER['ORIG_PATH_INFO'] : @getenv('ORIG_PATH_INFO');
            if (trim($path, '/') != '' && $path != "/".SELF)
            {
                // remove path and script information so we have good URI data
                $this->uri_protocol = 'ORIG_PATH_INFO';
                $this->uri_string   = str_replace($_SERVER['SCRIPT_NAME'], '', $path);
                return;
            }

            // We've exhausted all our options...
            $this->uri_string = '';
        }
        else
        {           
            if ($protocol == 'REQUEST_URI')
            {
                $this->uri_protocol = $protocol;
                $this->uri_string   = $this->_parse_request_uri();
                return;
            }

            $this->uri_string = (isset($_SERVER[$protocol])) ? $_SERVER[$protocol] : @getenv($protocol);
        }

        // If the URI contains only a slash we'll kill it
        if ($this->uri_string == '/')
        {
            $this->uri_string = '';
        }
    }

    // --------------------------------------------------------------------

    /**
     * Parse the REQUEST_URI
     *
     * Due to the way REQUEST_URI works it usually contains path info
     * that makes it unusable as URI data.  We'll trim off the unnecessary
     * data, hopefully arriving at a valid URI that we can use.
     *
     * @access    private
     * @return    string
     */
    public function _parse_request_uri()
    {
        if ( ! isset($_SERVER['REQUEST_URI']) OR $_SERVER['REQUEST_URI'] == '')
        {
            return '';
        }

        $request_uri = preg_replace("|/(.*)|", "\\1", str_replace("\\", "/", $_SERVER['REQUEST_URI']));

        if ($request_uri == '' OR $request_uri == SELF)
        {
            return '';
        }

        $fc_path = FCPATH.SELF;
        if (strpos($request_uri, '?') !== FALSE)
        {
            $fc_path .= '?';
        }

        $parsed_uri = explode("/", $request_uri);

        $i = 0;
        foreach(explode("/", $fc_path) as $segment)
        {
            if (isset($parsed_uri[$i]) && $segment == $parsed_uri[$i])
            {
                $i++;
            }
        }

        $parsed_uri = implode("/", array_slice($parsed_uri, $i));

        if ($parsed_uri != '')
        {
            $parsed_uri = '/'.$parsed_uri;
        }
        
        // IN REQUEST_URI mode the controller can work with any query string like this.
        // http://domain.com/controller/method?query_string=support for REQUEST_URI protocol
        // remove last query_string variables.
        // This functionality works natively in PATH_INFO mode.
        $parsed_uri = preg_replace('|(\?.*)|', '', $parsed_uri);

        return $parsed_uri;
    }

    // --------------------------------------------------------------------

    /**
    * Parse uri string for any possible file
    * extensions
    *
    * @param  string $segment
    * @return string
    */
    public function _parse_segment_extension($segment)
    {
        if(strpos($segment, '.') !== FALSE)
        {
            $allowed_extensions = config('uri_extensions');
            
            $extension = explode('.', $segment);
            $extension = end($extension);
            
            if(in_array($extension, $allowed_extensions))        
            {
                $this->extension = $extension;
                
                return str_replace('.'.$extension, '', $segment);
            }
        }

        return $segment;
    }

    // --------------------------------------------------------------------

    /**
    * Get extension of uri
    *
    * @return  string
    */
    public function extension()
    {
        if(isset($this->extension))
        {
            return $this->extension;
        }

        return str_replace('.', '', EXT);
    }

    // --------------------------------------------------------------------

    /**
     * Filter segments for malicious characters
     *
     * @access   private
     * @param    string
     * @return   string
     */
    public function _filter_uri($str)
    {
        // $class = lib('anyClass');  // Don't use any class in this level this will occur fatal errors !!!

    	if ($str != '' && config('permitted_uri_chars') != '' && config('enable_query_strings') == FALSE)
        {
            // preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
            // compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
            if ( ! preg_match('|^['.str_replace(array('\\-', '\-'), '-', preg_quote(config('permitted_uri_chars'), '-')).']+$|i', $str))
            {
                show_error('The URI you submitted has disallowed characters.', 400);
            }
        }

        // Convert programatic characters to entities and return
        return str_replace(array('$',     '(',     ')',     '%28',   '%29'), // Bad
                           array('&#36;', '&#40;', '&#41;', '&#40;', '&#41;'), // Good
                           $str);
    }
    

    // --------------------------------------------------------------------

    /**
     * Remove the suffix from the URL if needed
     *
     * @access    private
     * @return    void
     */
    public function _remove_url_suffix()
    {
        if  (config('url_suffix') != "")
        {
            $this->uri_string = preg_replace("|".preg_quote(config('url_suffix'))."$|", "", $this->uri_string);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Explode the URI Segments. The individual segments will
     * be stored in the $this->segments array.
     *
     * @access    private
     * @return    void
     */
    public function _explode_segments()
    {
        foreach(explode("/", preg_replace("|/*(.+?)/*$|", "\\1", $this->uri_string)) as $val)
        {
            // Filter segments for security
            $val = trim($this->_filter_uri($val));

            if ($val != '')
            {
                $this->segments[] = $this->_parse_segment_extension($val);
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Fetch a URI Segment
     *
     * This function returns the URI segment based on the number provided.
     *
     * @access   public
     * @param    integer
     * @param    bool
     * @return   string
     */
    public function segment($n, $no_result = FALSE)
    {
        return ( ! isset($this->segments[$n])) ? $no_result : $this->segments[$n];
    }

    // --------------------------------------------------------------------

    /**
     * Fetch a URI "routed" Segment ( Sub module isn't a rsegment based.)
     *
     * This function returns the re-routed URI segment (assuming routing rules are used)
     * based on the number provided.  If there is no routing this function returns the
     * same result as $this->segment()
     *
     * @access   public
     * @param    integer
     * @param    bool
     * @return   string
     */
    public function rsegment($n, $no_result = FALSE)
    {
        return ( ! isset($this->rsegments[$n])) ? $no_result : $this->rsegments[$n];
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Generate a key value pair from the URI string
    *
    * This function generates and associative array of URI data starting
    * at the supplied segment. For example, if this is your URI:
    *
    *    example.com/user/search/name/joe/location/UK/gender/male
    *
    * You can use this function to generate an array with this prototype:
    *
    * array (
    *            name => joe
    *            location => UK
    *            gender => male
    *         )
    *
    * @access   public
    * @param    integer    the starting segment number
    * @param    array    an array of default values
    * @return   array
    */
    public function uri_to_assoc($n = 3, $default = array())
    {
         return $this->_uri_to_assoc($n, $default, 'segment');
    }

    // --------------------------------------------------------------------
    
    /**
     * Identical to above only it uses the re-routed segment array
     *
     */
    public function ruri_to_assoc($n = 3, $default = array())
    {
         return $this->_uri_to_assoc($n, $default, 'rsegment');
    }

    // --------------------------------------------------------------------

    /**
     * Generate a key value pair from the URI string or Re-routed URI string
     *
     * @access   private
     * @param    integer    the starting segment number
     * @param    array    an array of default values
     * @param    string    which array we should use
     * @return   array
     */
    public function _uri_to_assoc($n = 3, $default = array(), $which = 'segment')
    {
        if ($which == 'segment')
        {
            $total_segments = 'total_segments';
            $segment_array = 'segment_array';
        }
        else
        {
            $total_segments = 'total_rsegments';
            $segment_array = 'rsegment_array';
        }

        if ( ! is_numeric($n))
        {
            return $default;
        }

        if (isset($this->keyval[$n]))
        {
            return $this->keyval[$n];
        }

        if ($this->$total_segments() < $n)
        {
            if (count($default) == 0)
            {
                return array();
            }

            $retval = array();
            foreach ($default as $val)
            {
                $retval[$val] = FALSE;
            }
            return $retval;
        }

        $segments = array_slice($this->$segment_array(), ($n - 1));

        $i = 0;
        $lastval = '';
        $retval  = array();
        foreach ($segments as $seg)
        {
            if ($i % 2)
            {
                $retval[$lastval] = $seg;
            }
            else
            {
                $retval[$seg] = FALSE;
                $lastval = $seg;
            }

            $i++;
        }

        if (count($default) > 0)
        {
            foreach ($default as $val)
            {
                if ( ! array_key_exists($val, $retval))
                {
                    $retval[$val] = FALSE;
                }
            }
        }

        // Cache the array for reuse
        $this->keyval[$n] = $retval;
        
        return $retval;
    }

    // --------------------------------------------------------------------

    /**
     * Generate a URI string from an associative array
     *
     *
     * @access   public
     * @param    array    an associative array of key/values
     * @return   array
     */
    public function assoc_to_uri($array)
    {
        $temp = array();
        foreach ((array)$array as $key => $val)
        {
            $temp[] = $key;
            $temp[] = $val;
        }

        return implode('/', $temp);
    }

    // --------------------------------------------------------------------

    /**
     * Fetch a URI Segment and add a trailing slash
     *
     * @access   public
     * @param    integer
     * @param    string
     * @return   string
     */
    public function slash_segment($n, $where = 'trailing')
    {
        return $this->_slash_segment($n, $where, 'segment');
    }

    // --------------------------------------------------------------------

    /**
     * Fetch a URI Segment and add a trailing slash
     *
     * @access   public
     * @param    integer
     * @param    string
     * @return   string
     */
    public function slash_rsegment($n, $where = 'trailing')
    {
        return $this->_slash_segment($n, $where, 'rsegment');
    }

    // --------------------------------------------------------------------

    /**
     * Fetch a URI Segment and add a trailing slash - helper function
     *
     * @access   private
     * @param    integer
     * @param    string
     * @param    string
     * @return   string
     */
    public function _slash_segment($n, $where = 'trailing', $which = 'segment')
    {
        if ($where == 'trailing')
        {
            $trailing    = '/';
            $leading    = '';
        }
        elseif ($where == 'leading')
        {
            $leading    = '/';
            $trailing    = '';
        }
        else
        {
            $leading    = '/';
            $trailing    = '/';
        }
        return $leading.$this->$which($n).$trailing;
    }

    // --------------------------------------------------------------------

    /**
     * Segment Array
     *
     * @access    public
     * @return    array
     */
    public function segment_array()
    {
        return $this->segments;
    }

    // --------------------------------------------------------------------

    /**
     * Routed Segment Array
     *
     * @access    public
     * @return    array
     */
    public function rsegment_array()
    {
        return $this->rsegments;
    }

    // --------------------------------------------------------------------

    /**
     * Total number of segments
     *
     * @access    public
     * @return    integer
     */
    public function total_segments()
    {
        return count($this->segments);
    }

    // --------------------------------------------------------------------

    /**
     * Total number of routed segments
     *
     * @access    public
     * @return    integer
     */
    public function total_rsegments()
    {
        return count($this->rsegments);
    }

    // --------------------------------------------------------------------

    /**
     * Fetch the entire URI string
     *
     * @access    public
     * @return    string
     */
    public function uri_string()
    {
        return $this->uri_string;
    }

    // --------------------------------------------------------------------

    /**
     * Fetch the entire Re-routed URI string
     *
     * @access    public
     * @return    string
     */
    public function ruri_string()
    {
        return '/'.implode('/', $this->rsegment_array()).'/';
    }

    // --------------------------------------------------------------------
    
    /**
     * Get the current server uri
     * protocol.
     * 
     * @access public
     * @return string
     */
    public function protocol()
    {
        return $this->uri_protocol;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get the complete request uri like native php
     * $_SERVER['REQUEST_URI'].
     * 
     * @access public
     * @param  boolean $urlencode
     * @return string
     */
    public function request_uri($urlencode = FALSE)
    {
       if(isset($_SERVER[$this->protocol()]))
       {
           return ($urlencode) ? urlencode($_SERVER[$this->protocol()]) : $_SERVER[$this->protocol()];
       }
       
       return FALSE;
    }

}
// END URI Class

/* End of file Uri.php */
/* Location: ./ob/uri/releases/0.0.1/uri.php */