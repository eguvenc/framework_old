<?php

/**
 * Obullo Framework (c) 2009 - 2012.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo
 * @author          obullo.com
 * @copyright       Obullo Team
 * @since           Version 1.0
 * @license
 */

/**
* Common.php
*
* @version 1.0
* @version 1.1 added removed OB_Library:factory added
*              lib_factory function
* @version 1.2 removed lib_factory function
* @version 1.3 renamed base "libraries" folder as "base"
* @version 1.4 added $var  and config_name vars for get_config()
* @version 1.5 added PHP5 library interface class, added spl_autoload_register()
*              renamed register_static() function, added replace support ..
*/

/**
* Register core libraries
* 
* @param string $realname
* @param boolean | object $new_object
* @param array $params_or_no_ins
*/
function core_class($realname, $new_object = NULL, $params_or_no_ins = '')
{
    static $new_objects = array();                
    
    $Class    = ucfirst(mb_strtolower($realname, config('charset')));
    $registry = OB_Registry::instance();
    
    // if we need to reset any registered object .. 
    // --------------------------------------------------------------------
    if(is_object($new_object))
    {
        $registry->unset_object($Class);
        $registry->set_object($Class, $new_object);
        
        return $new_object;
    }
    
    $getObject = $registry->get_object($Class);   
                                                   
    if ($getObject !== NULL)
    {
        return $getObject;
    }
                      
    if(file_exists(BASE .'libraries'. DS .'core'. DS .$Class. EXT))
    {
        if( ! isset($new_objects[$Class]) )  // check new object instance
        {
            require(BASE .'libraries'. DS .'core'. DS .$Class. EXT);
        }
        
        $classname = $Class;    // prepare classname

        if($params_or_no_ins === FALSE)
        {
            return TRUE;
        }

        $classname = 'OB_'.$Class;
        
        // __construct params support.
        // --------------------------------------------------------------------
        if($new_object == TRUE)
        {
            if(is_array($params_or_no_ins))  // construct support.
            {
                $Object = new $classname($params_or_no_ins);

            } else
            {
                $Object = new $classname();
            }
            
            $new_objects[$Class] = $Class;  // set new instance to static variable
        } 
        else 
        {
            if(is_array($params_or_no_ins)) // construct support.
            {
                $registry->set_object($Class, new $classname($params_or_no_ins));

            } else
            {
                $registry->set_object($Class, new $classname());
            }

            $Object = $registry->get_object($Class);
        }

        // return to singleton object.
        // --------------------------------------------------------------------
                      
        if(is_object($Object))
        {
            return $Object;
        }
        
    }
    else 
    {
        throw new Exception('The core class '.$Class. ' not found.');
    }

    return NULL;  // if register func return to null
                  // we will show a loader exception
}

// -------------------------------------------------------------------- 

/**
* load_class()
*
* Register base classes which start by OB_ prefix
*
* @access   private
* @param    string $class the class name being requested
* @param    array | bool $params_or_no_ins (__construct parameter ) | or | No Instantiate
* @version  0.1
* @version  0.2 removed OB_Library::factory()
*               added lib_factory() function
* @version  0.3 renamed base "libraries" folder as "base"
* @version  0.4 added extend to core libraries support
* @version  0.5 added $params_or_no_ins instantiate switch FALSE.
* @version  0.6 added $new_object instance param, added unset object.
* @version  0.7 added new instance support ( added $new_objects variable).
*
* @return   object  | NULL
*/
function load_class($realname, $new_object = NULL, $params_or_no_ins = '')
{
    static $new_objects       = array();               
    
    // Sub path support
    // --------------------------------------------------------------------
    $sub_path = '';
    if(strpos($realname, '/') > 0)
    {
        $paths    = explode('/', $realname);
        $realname = array_pop($paths);         // get file name
        $sub_path = DS . implode(DS, $paths);  // build sub path  ( e.g ./drivers/pager/)
    }
    
    $Class    = ucfirst(mb_strtolower($realname, config('charset')));
    $registry = OB_Registry::instance();
    
    // if we need to reset any registered object .. 
    // --------------------------------------------------------------------
    if(is_object($new_object))
    {
        $registry->unset_object($Class);
        $registry->set_object($Class, $new_object);
        
        return $new_object;
    }

    if($params_or_no_ins !== FALSE)  // No instantiate support.
    {
        $getObject = $registry->get_object($Class);

        if ($getObject !== NULL)
        {
            return $getObject;
        }
    }
    
    // No Instantiate Support.
    // --------------------------------------------------------------------
    if($params_or_no_ins === FALSE)
    {
        return TRUE;
    }
                                                  
    if(file_exists(BASE .'libraries'. $sub_path . DS . $Class. EXT))
    {
        if( ! isset($new_objects[$Class]) )  // check new object instance
        {
            require(BASE .'libraries'. $sub_path . DS . $Class. EXT);
        }
        
        $classname = $Class;    // prepare classname
        $classname = 'OB_'.$Class;
        
        // __construct() params support.
        // --------------------------------------------------------------------
        
        if($new_object == TRUE)
        {
            if(is_array($params_or_no_ins))  // construct support.
            {
                $Object = new $classname($params_or_no_ins);

            } else
            {
                $Object = new $classname();
            }
            
            $new_objects[$Class] = $Class;  // set new instance to static variable
        } 
        else 
        {
            if(is_array($params_or_no_ins)) // construct support.
            {
                $registry->set_object($Class, new $classname($params_or_no_ins));

            } else
            {
                $registry->set_object($Class, new $classname());
            }

            $Object = $registry->get_object($Class);
        }

        // return to singleton object.
        // --------------------------------------------------------------------
                      
        if(is_object($Object))
        {
            return $Object;
        }
        
    }
    else 
    {
        throw new Exception('The Obullo library '.$Class. ' not found.');
    }

    return NULL;  // if register func return to null
                  // we will show a loader exception
}

// --------------------------------------------------------------------

/**
* register_autoload();
* Autoload Just for php5 Libraries.
*
* @access   public
* @author   Ersin Guvenc
* @param    string $class name of the class.
*           You must provide real class name. (lowercase)
* @param    boolean $base base class or not
* @version  0.1
* @version  0.2 added base param
* @version  0.3 renamed base "libraries" folder as "base"
* @version  0.4 added php5 library support, added spl_autoload_register() func.
* @version  0.5 added replace and extend support
* @version  0.6 removed LoaderException to play nicely with other autoloaders.
*
* @return NULL | Exception
*/
function ob_autoload($real_name)
{    
    if(class_exists($real_name))
    {
        return;
    }
    
    $Class = $real_name;    
    
    // Database files.
    if($real_name == 'Model' OR $real_name == 'Vmodel')
    {
        require(BASE .'core'. DS .$real_name. EXT);
        
        return;
    }

    // __autoload libraries load support.
    // --------------------------------------------------------------------
    if(file_exists(MODULES .'libraries'. DS .$Class. EXT))
    {
        require(MODULES .'libraries'. DS .$Class. EXT);

        return;
    }
    
    return;
}

spl_autoload_register('ob_autoload', true);

// --------------------------------------------------------------------

/**
* Obullo library loader
* 
* @param string $class
* @param array | false $params_or_no_instance
* @param true | object $new_object
*/
if( ! function_exists('lib'))
{
    function lib($class, $params_or_no_instance = '', $new_object = NULL)
    {    
        //------------ Begin core classes --------------
        
        $class = mb_strtolower($class, config('charset'));

        if(strpos($class, 'ob/') === 0) // Obullo Libraries.
        {               
           $class = substr($class, 3);
           
           if(in_array($class, array('router', 'uri', 'module'), true))
           {
               return core_class($class, $new_object, $params_or_no_instance);
           }    
           
           if($class == 'output' || $class == 'config') // core clases but located in libraries directory.
           {
               return load_class($class, $new_object, $params_or_no_instance);
           }
        }

        //------------ End core classes --------------
        
        if($new_object === NULL || $new_object === FALSE)  // User Libraries.
        {   
            if(function_exists('i_hmvc'))  // allow using lib() function at bootstrap level.
            {
                if(i_hmvc()) // We must create new instance for each hmvc requests.
                {
                    $new_object = TRUE;
                }
            }
        }

        return load_class($class, $new_object, $params_or_no_instance);
    }
}

// --------------------------------------------------------------------

/**
* Loads the (static) configuration or language files.
*
* @access    private
* @author    Ersin Guvenc
* @param     string $filename file name
* @param     string $var variable of the file
* @param     string $folder folder of the file
* @version   0.1
* @version   0.2 added $config_name param
* @version   0.3 added $var variable
* @version   0.4 renamed function as get_static ,renamed $config_name as $filename, added $folder param
* @return    array
*/
function get_static($filename = 'config', $var = '', $folder = '')
{
    static $loaded    = array();
    static $variables = array();
    
    $key = trim($folder. DS .$filename. EXT);
    
    if ( ! isset($loaded[$key]))  // Just reqiure once !
    {
        #######################
        
        require($folder. DS .$filename. EXT);
        
        #######################
        
        if($var == '') 
        {
            $var = &$filename;
        }

        if($filename != 'autoload' AND $filename != 'constants')
        {
            if ( ! isset($$var) OR ! is_array($$var))
            {
                $error_msg = 'The static file '. $folder. DS .$filename. EXT .' file does not appear to be formatted correctly.';
                
                log_me('debug', $error_msg);
                
                throw new Exception($error_msg);
            }
        }

        $variables[$key] =& $$var;
        $loaded[$key] = $key;
     }

    return $variables[$key];
}

// --------------------------------------------------------------------

/**
* Get config file.
*
* @access   public
* @param    string $filename
* @param    string $var
* @return   array
*/
function get_config($filename = 'config', $var = '', $folder = '')
{
    $folder = ($folder == '') ? APP .'config' : $folder;
    
    if($filename == 'database')
    {
        $database   = get_static($filename, $var, APP .'config');

        return $database;
    }
    
    return get_static($filename, $var, $folder);
}

// --------------------------------------------------------------------

/**
* Gets a config item
*
* @access    public
* @param     string $config_name file name
* @version   0.1
* @version   0.2 added $config_name var
*            multiple config support
* @return    mixed
*/
function config($item, $config_name = 'config')
{
    static $config_item = array();

    if ( ! isset($config_item[$item]))
    {
        $config_name = get_config($config_name);

        if ( ! isset($config_name[$item]))
        {
            return FALSE;
        }

        $config_item[$item] = $config_name[$item];
    }

    return $config_item[$item];
}

function config_item($item = '')
{
    throw new Exception('config_item() deprecated, please use config().');
}

// --------------------------------------------------------------------

/**
* Gets a db configuration items
*
* @access    public
* @author    Ersin Guvenc
* @param     string $item
* @param     string $index 'default'
* @version   0.1
* @version   0.2 added multiple config fetch
* @return    mixed
*/
function db_item($item, $index = 'db')
{
    static $db_item = array();

    if ( ! isset($db_item[$index][$item]))
    {
        $database = get_config('database');
        
        if ( ! isset($database[$index][$item]))
        {
            return FALSE;
        }
        
        $db_item[$index][$item] = $database[$index][$item];
    }

    return $db_item[$index][$item];
}

// --------------------------------------------------------------------

/**
* Tests for file writability
*
* is_writable() returns TRUE on Windows servers when you really can't write to
* the file, based on the read-only attribute.  is_writable() is also unreliable
* on Unix servers if safe_mode is on.
*
* @access    private
* @return    void
*/
function is_really_writable($file)
{
    // If we're on a Unix server with safe_mode off we call is_writable
    if (DS == '/' AND @ini_get("safe_mode") == FALSE)
    {
        return is_writable($file);
    }

    // For windows servers and safe_mode "on" installations we'll actually
    // write a file then read it.  Bah...
    if (is_dir($file))
    {
        $file = rtrim($file, DS). DS .md5(rand(1,100));

        if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
        {
            return FALSE;
        }

        fclose($fp);
        @chmod($file, DIR_WRITE_MODE);
        @unlink($file);
        return TRUE;
    }
    elseif (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
    {
        return FALSE;
    }

    fclose($fp);
    
    return TRUE;
}

// --------------------------------------------------------------------

/**
* Determines if the current version of PHP is greater then the supplied value
*
* Since there are a few places where we conditionally test for PHP > 5
* we'll set a static variable.
*
* @access   public
* @param    string
* @return   bool
*/
function is_php($version = '5.0.0')
{
    static $_is_php = array();
    
    $version = (string)$version;

    if ( ! isset($_is_php[$version]))
    {
        $_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
    }

    return $_is_php[$version];
}

// ------------------------------------------------------------------------

/**
* Set HTTP Status Header
*
* @access   public
* @param    int     the status code
* @param    string    
* @return   void
*/
function set_status_header($code = 200, $text = '')
{
    $stati = array(
                        200    => 'OK',
                        201    => 'Created',
                        202    => 'Accepted',
                        203    => 'Non-Authoritative Information',
                        204    => 'No Content',
                        205    => 'Reset Content',
                        206    => 'Partial Content',

                        300    => 'Multiple Choices',
                        301    => 'Moved Permanently',
                        302    => 'Found',
                        304    => 'Not Modified',
                        305    => 'Use Proxy',
                        307    => 'Temporary Redirect',

                        400    => 'Bad Request',
                        401    => 'Unauthorized',
                        403    => 'Forbidden',
                        404    => 'Not Found',
                        405    => 'Method Not Allowed',
                        406    => 'Not Acceptable',
                        407    => 'Proxy Authentication Required',
                        408    => 'Request Timeout',
                        409    => 'Conflict',
                        410    => 'Gone',
                        411    => 'Length Required',
                        412    => 'Precondition Failed',
                        413    => 'Request Entity Too Large',
                        414    => 'Request-URI Too Long',
                        415    => 'Unsupported Media Type',
                        416    => 'Requested Range Not Satisfiable',
                        417    => 'Expectation Failed',

                        500    => 'Internal Server Error',
                        501    => 'Not Implemented',
                        502    => 'Bad Gateway',
                        503    => 'Service Unavailable',
                        504    => 'Gateway Timeout',
                        505    => 'HTTP Version Not Supported'
                    );

    if ($code == '' OR ! is_numeric($code))
    {
        show_error('Status codes must be numeric', 500);
    }

    if (isset($stati[$code]) AND $text == '')
    {                
        $text = $stati[$code];
    }
    
    if ($text == '')
    {
        show_error('No status text available.  Please check your status code number or supply your own message text.', 500);
    }
    
    $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

    if (substr(php_sapi_name(), 0, 3) == 'cgi')
    {
        header("Status: {$code} {$text}", TRUE);
    }
    elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')
    {
        header($server_protocol." {$code} {$text}", TRUE, $code);
    }
    else
    {
        header("HTTP/1.1 {$code} {$text}", TRUE, $code);
    }
}

//----------------------------------------------------------------------- 

/**
* 404 Page Not Found Handler
*
* @access   private
* @param    string
* @return   string
*/
if( ! function_exists('show_404')) 
{
    function show_404($page = '')
    {    
        log_me('error', '404 Page Not Found --> '.$page, false, true);

        echo show_http_error('404 Page Not Found', $page, 'ob_404', 404);

        exit();
    }
}

// -------------------------------------------------------------------- 

/**
* Manually Set General Http Errors
* 
* @param string $message
* @param int    $status_code
* @param int    $heading
* 
* @version 0.1
* @version 0.2  added custom $heading params for users
*/
if( ! function_exists('show_error')) 
{
    function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered')
    {
        log_me('error', 'HTTP Error --> '.$message, false, true);
        
        // Some times we use utf8 chars in errors.
        header('Content-type: text/html; charset='.config('charset')); 
        
        echo show_http_error($heading, $message, 'ob_general', $status_code);
        
        exit();
    }
}
                   
// --------------------------------------------------------------------

/**
 * General Http Errors
 *
 * @access   private
 * @param    string    the heading
 * @param    string    the message
 * @param    string    the template name
 * @param    int       header status code
 * @return   string
 */
if( ! function_exists('show_http_error')) 
{
    function show_http_error($heading, $message, $template = 'ob_general', $status_code = 500)
    {
        set_status_header($status_code);

        $message = implode('<br />', ( ! is_array($message)) ? array($message) : $message);
        
        if(defined('STDIN'))  // If Command Line Request
        {
            return '['.$heading.']: The url ' .$message. ' you requested was not found.'."\n";
        }
        
        ob_start();
        include(APP. 'core'. DS .'errors'. DS .$template. EXT);
        $buffer = ob_get_clean();
        
        return $buffer;
    }
}

// -------------------------------------------------------------------- 

/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('remove_invisible_characters'))
{
    function remove_invisible_characters($str, $url_encoded = TRUE)
    {
        $non_displayables = array();

        // every control character except newline (dec 10)
        // carriage return (dec 13), and horizontal tab (dec 09)

        if ($url_encoded)
        {
            $non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

        do
        {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        }
        while ($count);

        return $str;
    }
}

// END Common.php File

/* End of file Common.php */
/* Location: ./obullo/core/Common.php */