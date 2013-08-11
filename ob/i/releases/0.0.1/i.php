<?php
namespace Ob\i {
    
    // ------------------------------------------------------------------------

    /**
    * "i" Helper for Input class
    *
    * @package     Obullo
    * @subpackage  i
    * @category    input
    * @author      Obullo Team
    * @link        
    */
    Class start
    {
        function __construct()
        {
            \Ob\log\me('debug', 'I Helper Initialized.');
        }
    }
    
    
    /**
    * Fetch an item from the GET array
    *
    * @access   public
    * @param    string
    * @param    bool
    * @param    bool    Use global get values instead of HMVC values.
    * @return   string
    */
    function get($index = '', $xss_clean = FALSE, $use_global_var = FALSE)
    {
        $GET = ($use_global_var) ? $GLOBALS['_GET_BACKUP']: $_GET; // _GET_BACKUP = Hmvc local get values
        
        return \Ob\Input\Input::getInstance()->_fetch_from_array($GET, $index, $xss_clean);
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
    function post($index = '', $xss_clean = FALSE, $use_global_var = FALSE)
    {
        $POST = ($use_global_var) ? $GLOBALS['_POST_BACKUP']: $_POST; // _POST_BACKUP = Hmvc local post values

        return \Ob\Input\Input::getInstance()->_fetch_from_array($POST, $index, $xss_clean);
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
    function request($index = '', $xss_clean = FALSE, $use_global_var = FALSE)
    {
        $REQUEST = ($use_global_var) ? $GLOBALS['_REQUEST_BACKUP']: $_REQUEST; // _REQUEST_BACKUP = Hmvc local request values

        return \Ob\Input\Input::getInstance()->_fetch_from_array($REQUEST, $index, $xss_clean);
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
    function get_post($index = '', $xss_clean = FALSE, $use_global_var = FALSE)
    {
        $POST = ($use_global_var) ? $GLOBALS['_POST_BACKUP'] : $_POST; //  _POST_BACKUP = Hmvc local post values.

        if ( ! isset($POST[$index]) )
        {
            return get($index, $xss_clean, $use_global_var);
        }
        else
        {
            return post($index, $xss_clean, $use_global_var);
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
    function cookie($index = '', $xss_clean = FALSE)
    {
        return \Ob\Input\Input::getInstance()->_fetch_from_array($_COOKIE, $index, $xss_clean);
    }

    // --------------------------------------------------------------------

    /**
    * Fetch the IP Address
    *
    * @access    public
    * @return    string
    */
    function ip_address()
    {
        return \Ob\Input\Input::getInstance()->ip_address();
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
    function valid_ip($ip)
    {        
        return \Ob\Input\Input::getInstance()->valid_ip($ip);
    }
    
    // --------------------------------------------------------------------

    /**
    * User Agent
    *
    * @access    public
    * @return    string
    */
    function user_agent()
    {
        $input = \Ob\Input\Input::getInstance();

        if ($input->user_agent !== FALSE)
        {
            return $input->user_agent;
        }

        $input->user_agent = ( ! isset($_SERVER['HTTP_USER_AGENT'])) ? FALSE : $_SERVER['HTTP_USER_AGENT'];

        return $input->user_agent;
    }

    // --------------------------------------------------------------------

    /**
    * Filename Security
    *
    * @access   public
    * @param    string
    * @return   string
    */
    function filename_security($str)
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
    
    // --------------------------------------------------------------------

    /**
    * Check Request Is Ajax.
    * 
    * Test to see if a request contains the HTTP_X_REQUESTED_WITH header
    * 
    * @return boolean
    */
    function ajax()
    {    
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']))
        {
            return ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
        }
        
        return FALSE;
    } 

    // --------------------------------------------------------------------

    /**
    * Check is Request Command Line.
    * 
    * @return boolean
    */
    function cli()
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

    // --------------------------------------------------------------------

    /**
    * Check is Request Task.
    * 
    * @return boolean
    */
    function task()
    {    
        if(defined('TASK'))
        {
            return TRUE;
        }
        
        return FALSE;
    } 
    // --------------------------------------------------------------------

    /**
    * Check is Request HMVC.
    * 
    * @return boolean
    */
    function hmvc()
    {    
        if(\Ob\Router\Router::getInstance()->is_hmvc())
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
}