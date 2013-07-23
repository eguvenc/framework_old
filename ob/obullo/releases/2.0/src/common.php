<?php
namespace Ob {

    /**
    * Common.php
    *
    * @version 1.0
    */
    
    // -------------------------------------------------------------------- 

    /**
    * Grab Obullo Super Object
    *
    * @param object $new_istance  
    */
    function getInstance($new_instance = '') 
    { 
        if(is_object($new_instance))  // fixed HMVC object type of integer bug in php 5.1.6
        {
            Controller::_ob_getInstance_($new_instance);
        }

        return Controller::_ob_getInstance_(); 
    }

    // --------------------------------------------------------------------

    /**
    * Autoload php5 files.

    * @param string $realname
    * @return
    */
    function autoloader($realname)
    {           
        if(class_exists($realname))
        {  
            return;
        }
        echo $realname.'<br>';
        
        /*            
        if($class !== 'Ob\log\log' AND $class == 'Ob\error\error')
        {
           print_r(get_declared_classes()); exit;
        }
                if(in_array((string)$class, get_declared_classes(), true))
        { 
            return;
        }
        */
        $packages = get_config('packages');

        //--------------- OB PACKAGE LOADER ---------------//
        
        if(strpos($realname, 'Ob\\') === 0)  // get modules from ob/ directory.
        {  
            $ob_parts   = explode('\\', $realname);
            $ob_library = strtolower($ob_parts[1]);

            $package_filename = mb_strtolower($ob_library, config('charset'));

            if( ! isset($packages['dependencies'][$package_filename]['component'])) //  check package Installed.
            {
                exit('The package '.$package_filename.' not installed. Please update your package.json and run obm update.');
            }

            require_once(OB_MODULES .$ob_library. DS .'releases'. DS .$packages['dependencies'][$ob_library]['version']. DS .$ob_library. EXT);
            return;
        }

                
        //--------------- MODEL LOADER ---------------//
        
        if($realname == 'Ob\Model') // Core model file.
        {
            require(OB_MODULES .'obullo'. DS .'releases'. DS .$packages['dependencies']['model']['version']. DS .'model'. EXT);
            return;
        }
        
        if(strpos($realname, 'Ob\Model\\') === 0) // User model files.
        {
            $model_parts = explode('\\', $realname);

            if(isset($model_parts[2]))  //  Directory Request
            {
                if($model_parts[2] == 'Odm')
                {
                    require(OB_MODULES .'odm'. DS .'releases'. DS .$packages['dependencies']['odm']['version']. DS .'odm'. EXT);
                    return;
                }

                $model_path = MODULES .strtolower($model_parts[1]) . DS .'models'. DS .strtolower($model_parts[2]). EXT;
            } 
            else 
            {   
                $model_path = MODULES .'models'. DS .strtolower($model_parts[1]). EXT;
            }

            require($model_path);
            return;
        }
        
        //--------------- VIEW LOADER ---------------//
        
        if(strpos($realname, 'Ob\vi\\') === 0)
        {
            $view_parts = explode('\\', $realname);

            if(isset($view_parts[3]))  //  Directory Request
            {
                $view_path = MODULES .strtolower($view_parts[1]) . DS .'vi'. DS .strtolower($view_parts[2]). EXT;
            } 
            else 
            {   
                $view_path = MODULES .'vi'. DS .strtolower($view_parts[1]). EXT;
                
                // echo $view_path; exit;
            }

            require($view_path);
            return;
        }
        
    }

    spl_autoload_register('Ob\autoloader', true);

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

    // --------------------------------------------------------------------

    /**
    * Gets a db configuration items
    *
    * @access    public
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
    *  Check requested obullo package
    *  whether to installed.
    */
    function package_exists($package)
    {
        $packages = get_config('packages');

        if(isset($packages['dependencies'][$package]['component']))
        {
            return TRUE;
        }

        return FALSE;
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
    function show_404($page = '')
    {    
        \Ob\log\me('error', '404 Page Not Found --> '.$page, false, true);

        echo show_http_error('404 Page Not Found', $page, 'ob_404', 404);

        exit();
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
    function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered')
    {
        \Ob\log\me('error', 'HTTP Error --> '.$message, false, true);

        // Some times we use utf8 chars in errors.
        header('Content-type: text/html; charset='.config('charset')); 

        echo show_http_error($heading, $message, 'ob_general', $status_code);

        exit();
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
    function show_http_error($heading, $message, $template = 'ob_general', $status_code = 500)
    {
        set_status_header($status_code);

        $message = implode('<br />', ( ! is_array($message)) ? array($message) : $message);
        
        if(defined('STDIN'))  // If Command Line Request
        {
            return '['.$heading.']: The url ' .$message. ' you requested was not found.'."\n";
        }
        
        ob_start();
        include(APP .'errors'. DS .$template. EXT);
        $buffer = ob_get_clean();
        
        return $buffer;
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

            if (($fp = @fopen($file, 'ab')) === FALSE)
            {
                return FALSE;
            }

            fclose($fp);
            @chmod($file, '0777');
            @unlink($file);
            return TRUE;
        }
        elseif (($fp = @fopen($file, 'ab')) === FALSE)
        {
            return FALSE;
        }

        fclose($fp);

        return TRUE;
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

// END common.php File

/* End of file common.php */
/* Location: ./ob/obullo/releases/2.0/src/common.php */