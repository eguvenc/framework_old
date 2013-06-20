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
 * Obullo Url Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Guvenc
 * @link
 */

/**
* Base URL
*
* Returns the "base_url" item from your config file
*
* @access    public
* @return    string
*/
if ( ! function_exists('base_url'))
{
    function base_url($uri = '')
    {
        return lib('ob/Config')->base_url($uri);
    }
}

// ------------------------------------------------------------------------

/**
* Public URL
*
* Returns the "public_url" item from your config file
*
* @access    public
* @param     string url
* @abstract  bool $no_slash  no trailing slash
* @return    string
*/
if ( ! function_exists('public_url'))
{
    function public_url($uri = '', $no_ext_uri_slash = FALSE, $no_folder = FALSE)
    {
        return lib('ob/Config')->public_url($uri, $no_folder, $no_ext_uri_slash);
    }
}
// ------------------------------------------------------------------------

/**
* Site URL
*
* Create a local URL based on your basepath. Segments can be passed via the
* first parameter either as a string or an array.
*
* @access    public
* @param     string url
* @param     bool  $suffix switch off suffix by manually if its true in config.php
* @return    string
*/
if ( ! function_exists('site_url'))
{
    function site_url($uri = '', $suffix = TRUE)
    {
        return lib('ob/Config')->site_url($uri, $suffix);
    }
}
// ------------------------------------------------------------------------

/**
* Get current url
*
* @access   public
* @return   string
*/
if ( ! function_exists('current_url'))
{
    function current_url()
    {
        return lib('ob/Config')->site_url(lib('ob/Uri')->uri_string());
    }
}
// ------------------------------------------------------------------------

/**
* The extension of URI
*
* Returns the "extension of url" item that 
* allowed from your config file.
*
* example.com/module/controller/post.json
* 
* @access    public
* @return    string
*/
if ( ! function_exists('uri_extension'))
{
    function uri_extension()
    {
        return lib('ob/Uri')->extension();
    }
}
// ------------------------------------------------------------------------

/**
* Get current module name
*
* @access   public
* @param    string uri
* @return   string
*/
if ( ! function_exists('module'))
{
    function module($uri = '')
    {
        $module = lib('ob/Router')->fetch_directory();
        
        if($uri == '')
        {
            return $module; 
        }

        return $module .'/'. ltrim($uri, '/');
    }
}

// ------------------------------------------------------------------------

/**
* Anchor Link
*
* Creates an anchor based on the local URL.
*
* @access    public
* @param     string    the URL
* @param     string    the link title
* @param     mixed     any attributes
* @param     bool      switch off suffix by manually
* @version   0.1
* @version   0.2       Sharp character url support
* @version   0.3       Added $suffix parameter
* @return    string
*/
if ( ! function_exists('anchor'))
{
    function anchor($uri = '', $title = '', $attributes = '', $suffix = TRUE)
    {
        $ssl = FALSE;  // ssl support
        if(strpos($uri, 'https://') === 0)
        {
            if(config('ssl')) // Global ssl config.
            {
                $ssl = TRUE;
            }
            
            $uri = str_replace('https://',  '',  $uri);
        }

        $title = (string) $title;
        $sharp = FALSE;

        // ' # ' sharp support for anchors. ( Obullo changes )..
        if(strpos($uri, '#') > 0)
        {
            $sharp_uri = explode('#', $uri);
            $uri       = $sharp_uri[0];
            $sharp     = TRUE;
        }

        if ( ! is_array($uri))
        {
            $site_url = ( ! preg_match('!^\w+://! i', $uri)) ? lib('ob/Config')->site_url($uri, $suffix) : $uri;
        }
        else
        {
            $site_url = lib('ob/Config')->site_url($uri, $suffix);
        }

        if ($title == '')
        {
            $title = $site_url;
        }

        if ($attributes != '')
        {
            $attributes = _parse_attributes($attributes);
        }

        if($sharp == TRUE AND isset($sharp_uri[1]))
        {
            $site_url = $site_url.'#'.$sharp_uri[1];  // Obullo changes..
        }

        # if ssl used do not use https:// for standart anchors.
        # if your HTTP server NGINX add below the line to your fastcgi_params file.
        # fastcgi_param  HTTPS		  $ssl_protocol;
        # then $_SERVER['HTTPS'] variable will be available for PHP (fastcgi).

        if(isset($_SERVER['HTTPS']) AND $_SERVER['HTTPS'] != '' AND $_SERVER['HTTPS'] != 'off')
        {
            if($ssl == FALSE)
            {
                $site_url = rtrim(config('domain_root'), '/') . $site_url;
            }
        }

        if($ssl)
        {
            $site_url = rtrim(config('domain_root'), '/') . $site_url;
            $site_url = str_replace('http://',  'https://',  $site_url);
        }

        return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
    }
}
// ------------------------------------------------------------------------

/**
* Anchor Link - Pop-up version
*
* Creates an anchor based on the local URL. The link
* opens a new window based on the attributes specified.
*
* @access	public
* @param	string	the URL
* @param	string	the link title
* @param	mixed	any attributes
* @param    bool    switch off suffix by manually
* @version  0.1     added suffix parameters
* @return	string
*/
if ( ! function_exists('anchor_popup'))
{
    function anchor_popup($uri = '', $title = '', $attributes = FALSE, $suffix = TRUE)
    {
        $ssl = FALSE;  // ssl support
        if(strpos($uri, 'https://') === 0)
        {
            if(config('ssl')) // Global ssl config.
            {
                $ssl = TRUE;
            }
            
            $uri = str_replace('https://',  '',  $uri);
        }

        $title = (string) $title;

        $site_url = ( ! preg_match('!^\w+://! i', $uri)) ? lib('ob/Config')->site_url($uri, $suffix) : $uri;

        # if ssl used do not use https:// for standart anchors.
        # if your HTTP server NGINX add below the line to your fastcgi_params file.
        # fastcgi_param  HTTPS		  $ssl_protocol;
        # then $_SERVER['HTTPS'] variable will be available for PHP (fastcgi).

        if(isset($_SERVER['HTTPS']) AND $_SERVER['HTTPS'] != '' AND $_SERVER['HTTPS'] != 'off')
        {
            if($ssl == FALSE)
            {
                $site_url = rtrim(config('domain_root'), '/') . $site_url;
            }
        }

        if($ssl)
        {
            $site_url = rtrim(config('domain_root'), '/') . $site_url;
            $site_url = str_replace('http://',  'https://',  $site_url);
        }

        if ($title == '')
        {
                $title = $site_url;
        }

        if ($attributes === FALSE)
        {
                return "<a href='javascript:void(0);' onclick=\"window.open('".$site_url."', '_blank');\">".$title."</a>";
        }

        if ( ! is_array($attributes))
        {
                $attributes = array();
        }

        foreach (array('width' => '800', 'height' => '600', 'scrollbars' => 'yes', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0', ) as $key => $val)
        {
                $atts[$key] = ( ! isset($attributes[$key])) ? $val : $attributes[$key];
                unset($attributes[$key]);
        }

        if ($attributes != '')
        {
                $attributes = _parse_attributes($attributes);
        }

        return "<a href='javascript:void(0);' onclick=\"window.open('".$site_url."', '_blank', '"._parse_attributes($atts, TRUE)."');\"$attributes>".$title."</a>";
    }
}
// ------------------------------------------------------------------------

/**
* Mailto Link
*
* @access	public
* @param	string	the email address
* @param	string	the link title
* @param	mixed 	any attributes
* @return	string
*/
if ( ! function_exists('mailto'))
{
    function mailto($email, $title = '', $attributes = '')
    {
        $title = (string) $title;

        if ($title == "")
        {
                $title = $email;
        }

        $attributes = _parse_attributes($attributes);

        return '<a href="mailto:'.$email.'"'.$attributes.'>'.$title.'</a>';
    }
}
// ------------------------------------------------------------------------

/**
* Encoded Mailto Link
*
* Create a spam-protected mailto link written in Javascript
*
* @access	public
* @param	string	the email address
* @param	string	the link title
* @param	mixed 	any attributes
* @return	string
*/
if ( ! function_exists('safe_mailto'))
{
    function safe_mailto($email, $title = '', $attributes = '')
    {
        $title = (string) $title;

        if ($title == "")
        {
                $title = $email;
        }

        for ($i = 0; $i < 16; $i++)
        {
                $x[] = substr('<a href="mailto:', $i, 1);
        }

        for ($i = 0; $i < strlen($email); $i++)
        {
                $x[] = "|" . ord(substr($email, $i, 1));
        }

        $x[] = '"';

        if ($attributes != '')
        {
                if (is_array($attributes))
                {
                        foreach ($attributes as $key => $val)
                        {
                                $x[] =  ' '.$key.'="';
                                for ($i = 0; $i < strlen($val); $i++)
                                {
                                        $x[] = "|" . ord(substr($val, $i, 1));
                                }
                                $x[] = '"';
                        }
                }
                else
                {
                        for ($i = 0; $i < strlen($attributes); $i++)
                        {
                                $x[] = substr($attributes, $i, 1);
                        }
                }
        }

        $x[] = '>';

        $temp = array();
        for ($i = 0; $i < strlen($title); $i++)
        {
                $ordinal = ord($title[$i]);

                if ($ordinal < 128)
                {
                        $x[] = "|".$ordinal;
                }
                else
                {
                        if (count($temp) == 0)
                        {
                                $count = ($ordinal < 224) ? 2 : 3;
                        }

                        $temp[] = $ordinal;
                        if (count($temp) == $count)
                        {
                                $number = ($count == 3) ? (($temp['0'] % 16) * 4096) + (($temp['1'] % 64) * 64) + ($temp['2'] % 64) : (($temp['0'] % 32) * 64) + ($temp['1'] % 64);
                                $x[] = "|".$number;
                                $count = 1;
                                $temp = array();
                        }
                }
        }

        $x[] = '<'; $x[] = '/'; $x[] = 'a'; $x[] = '>';

        $data['x'] = array_reverse($x);

        return view('ob/safe_mail', $data);
    }
}

// ------------------------------------------------------------------------

/**
* Auto-linker
*
* Automatically links URL and Email addresses.
* Note: There's a bit of extra code here to deal with
* URLs or emails that end in a period.  We'll strip these
* off and add them after the link.
*
* @access	public
* @param	string	the string
* @param	string	the type: email, url, or both
* @param	bool 	whether to create pop-up links
* @return	string
*/
if ( ! function_exists('auto_url'))
{
    function auto_link($str, $type = 'both', $popup = FALSE)
    {
        if ($type != 'email')
        {
                if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches))
                {
                        $pop = ($popup == TRUE) ? " target=\"_blank\" " : "";

                        for ($i = 0; $i < count($matches['0']); $i++)
                        {
                                $period = '';
                                if (preg_match("|\.$|", $matches['6'][$i]))
                                {
                                        $period = '.';
                                        $matches['6'][$i] = substr($matches['6'][$i], 0, -1);
                                }

                                $str = str_replace($matches['0'][$i],
                                                                        $matches['1'][$i].'<a href="http'.
                                                                        $matches['4'][$i].'://'.
                                                                        $matches['5'][$i].
                                                                        $matches['6'][$i].'"'.$pop.'>http'.
                                                                        $matches['4'][$i].'://'.
                                                                        $matches['5'][$i].
                                                                        $matches['6'][$i].'</a>'.
                                                                        $period, $str);
                        }
                }
        }

        if ($type != 'url')
        {
                if (preg_match_all("/([a-zA-Z0-9_\.\-\+]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i", $str, $matches))
                {
                        for ($i = 0; $i < count($matches['0']); $i++)
                        {
                                $period = '';
                                if (preg_match("|\.$|", $matches['3'][$i]))
                                {
                                        $period = '.';
                                        $matches['3'][$i] = substr($matches['3'][$i], 0, -1);
                                }

                                $str = str_replace($matches['0'][$i], safe_mailto($matches['1'][$i].'@'.$matches['2'][$i].'.'.$matches['3'][$i]).$period, $str);
                        }
                }
        }

        return $str;
    }
}
// ------------------------------------------------------------------------

/**
* Prep URL
*
* Simply adds the http:// part if missing
*
* @access	public
* @param	string	the URL
* @return	string
*/
if ( ! function_exists('prep_url'))
{
    function prep_url($str = '')
    {
        if ($str == 'http://' OR $str == '')
        {
                return '';
        }

        if ( ! parse_url($str, PHP_URL_SCHEME))
        {
                $str = 'http://'.$str;
        }

        return $str;
    }
}
// ------------------------------------------------------------------------

/**
* Create URL Title
*
* Takes a "title" string as input and creates a
* human-friendly URL string with either a dash
* or an underscore as the word separator.
*
* @access	public
* @param	string	the string
* @param	string	the separator: dash, or underscore
* @return	string
*/
if ( ! function_exists('url_title'))
{
    function url_title($str, $separator = 'dash', $lowercase = FALSE)
    {
        if ($separator == 'dash')
        {
                $search		= '_';
                $replace	= '-';
        }
        else
        {
                $search		= '-';
                $replace	= '_';
        }

        $trans = array(
                                        '&\#\d+?;'				=> '',
                                        '&\S+?;'				=> '',
                                        '\s+'					=> $replace,
                                        '[^a-z0-9\-\._]'		=> '',
                                        $replace.'+'			=> $replace,
                                        $replace.'$'			=> $replace,
                                        '^'.$replace			=> $replace,
                                        '\.+$'					=> ''
                                  );

        $str = strip_tags($str);

        foreach ($trans as $key => $val)
        {
                $str = preg_replace("#".$key."#i", $val, $str);
        }

        if ($lowercase === TRUE)
        {
                $str = strtolower($str);
        }

        return trim(stripslashes($str));
    }
}
// ------------------------------------------------------------------------

/**
* Header Redirect
*
* Header redirect in two flavors
* For very fine grained control over headers, you could use the Output
* Library's set_header() function.
*
* @access   public
* @param    string    the URL
* @param    string    the method: location or refresh[param]
* @version  0.1       added sharp support and suffix parameter
* @return   string
*/
if ( ! function_exists('redirect'))
{
    function redirect($uri = '', $method = 'location', $http_response_code = 302, $suffix = TRUE)
    {
        if ( ! preg_match('#^https?://#i', $uri))
        {
            $sharp = FALSE;

            // ' # ' sharp support for urls. ( Obullo changes )..
            if(strpos($uri, '#') > 0)
            {
                $sharp_uri = explode('#', $uri);
                $uri       = $sharp_uri[0];
                $sharp     = TRUE;
            }

            $uri = lib('ob/Config')->site_url($uri, $suffix);

            if($sharp == TRUE AND isset($sharp_uri[1]))
            {
                $uri = $uri.'#'.$sharp_uri[1];  // Obullo changes..
            }
        }

        if(strpos($method, '['))    // Obullo changes.. refresh parameter ..
        {
            $index = explode('[', $method);
            $param = str_replace(']', '', $index[1]);

            header("Refresh:$param;url=".$uri);
            return;
        }
        
        switch($method)
        {
            case 'refresh'    : header("Refresh:0;url=".$uri);
                break;
            default           : header("Location: ".$uri, TRUE, $http_response_code);
                break;
        }
        exit;
    }
}

// ------------------------------------------------------------------------

/**
* Parse out the attributes
*
* Some of the functions use this
*
* @access	private
* @param	array
* @param	bool
* @return	string
*/
if ( ! function_exists('_parse_attributes'))
{
    function _parse_attributes($attributes, $javascript = FALSE)
    {
        if (is_string($attributes))
        {
                return ($attributes != '') ? ' '.$attributes : '';
        }

        $att = '';
        foreach ($attributes as $key => $val)
        {
                if ($javascript == TRUE)
                {
                        $att .= $key . '=' . $val . ',';
                }
                else
                {
                        $att .= ' ' . $key . '="' . $val . '"';
                }
        }

        if ($javascript == TRUE AND $att != '')
        {
                $att = substr($att, 0, -1);
        }

        return $att;
    }
}

/* End of file url.php */
/* Location: ./obullo/helpers/url.php */