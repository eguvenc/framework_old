<?php
namespace i {

    /**
    * "i" ( input ) Helper for Input class
    *
    * @package     Ob
    * @subpackage  i
    * @category    input
    * @link        
    */
    
    Class start
    {
        function __construct()
        {
            \log\me('debug', 'I Helper Initialized.');
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
    function get($index = '', $xss_clean = false, $use_global_var = false)
    {
        $GET = ($use_global_var) ? $GLOBALS['_GET_BACKUP']: $_GET; // _GET_BACKUP = Hmvc local get values
        
        $input = '\\'.getComponent('input');
        
        return $input::getInstance()->_fetchFromArray($GET, $index, $xss_clean);
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
    function post($index = '', $xss_clean = false, $use_global_var = false)
    {
        $POST = ($use_global_var) ? $GLOBALS['_POST_BACKUP']: $_POST; // _POST_BACKUP = Hmvc local post values

        $input = '\\'.getComponent('input');
        
        return $input::getInstance()->_fetchFromArray($POST, $index, $xss_clean);
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
    function request($index = '', $xss_clean = false, $use_global_var = false)
    {
        $REQUEST = ($use_global_var) ? $GLOBALS['_REQUEST_BACKUP']: $_REQUEST; // _REQUEST_BACKUP = Hmvc local request values

        $input = '\\'.getComponent('input');
        
        return $input::getInstance()->_fetchFromArray($REQUEST, $index, $xss_clean);
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
    function getPost($index = '', $xss_clean = false, $use_global_var = false)
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
    function cookie($index = '', $xss_clean = false)
    {
        $input = '\\'.getComponent('input');
        
        return $input::getInstance()->_fetchFromArray($_COOKIE, $index, $xss_clean);
    }

    // --------------------------------------------------------------------

    /**
    * Fetch the IP Address
    *
    * @access    public
    * @return    string
    */
    function ip()
    {
        $input = '\\'.getComponent('input');
        
        return $input::getInstance()->ip();
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
    function validIp($ip)
    {        
        $input = '\\'.getComponent('input');
        
        return $input::getInstance()->validIp($ip);
    }
    
    // --------------------------------------------------------------------

    /**
    * User Agent
    *
    * @access    public
    * @return    string
    */
    function userAgent()
    {
        $inputClass = '\\'.getComponent('input');
        $input = $inputClass::getInstance();

        if ($input->user_agent !== false)
        {
            return $input->user_agent;
        }

        $input->user_agent = ( ! isset($_SERVER['HTTP_USER_AGENT'])) ? false : $_SERVER['HTTP_USER_AGENT'];

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
    function filenameSecurity($str)
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
        
        return false;
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
            return false;
        }
        
        if(defined('STDIN'))
        {
            return true;
        }
        
        return false;
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
            return true;
        }
        
        return false;
    } 
    // --------------------------------------------------------------------

    /**
    * Check is Request HMVC.
    * 
    * @return boolean
    */
    function hmvc()
    {   
        $router = '\\'.getComponent('router');
        
        if($router::getInstance()->isHmvc())
        {
            return true;
        }
        
        return false;
    }
    
}

/* End of file i.php */
/* Location: ./ob/i/releases/0.0.1/i.php */