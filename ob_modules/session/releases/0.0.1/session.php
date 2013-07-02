<?php 

/**
* @see Chapter / Helpers / Sessions
* 
* @param    mixed $params
* @version  0.1
* @version  0.2  added extend support for driver files.
*/
if( ! function_exists('sess_start')) 
{
    function sess_start($params = array())
    {   
        static $session_start = NULL;
        
        if ($session_start == NULL)
        {
            $config = Config::getInstance();
            $driver = (isset($params['sess_driver'])) ? $params['sess_driver'] : $config->item('sess_driver');
           
            $packages = get_config('packages');
    
            require(OB_MODULES .'session'. DS .'releases'. DS .$packages['dependencies']['session']['version']. DS .'src'. DS .'session_'.$driver.EXT);
            
            _sess_start($params); // Start the sessions
            
            $session_start = TRUE;
            
            return TRUE;
        }
        
        log_me('debug', "Sessions started"); 
        
        return FALSE;
    }
}

/**
 * Session Class
 *
 * @package	Obullo
 * @subpackage	session
 * @category	Sessions
 * @author      Obullo Team
 * @link	
 */
Class Session {
    
    public $db;
    public $sess_encrypt_cookie  = FALSE;
    public $sess_expiration      = '7200';
    public $sess_match_ip        = FALSE;
    public $sess_match_useragent = TRUE;
    public $sess_cookie_name     = 'ob_session';
    public $cookie_prefix        = '';
    public $cookie_path          = '';
    public $cookie_domain        = '';
    public $sess_time_to_update  = 300;
    public $encryption_key       = '';
    public $flashdata_key        = 'flash';
    public $time_reference       = 'time';
    public $gc_probability       = 5;
    public $sess_id_ttl          = '';
    public $userdata             = array();
    
    public static $instance;
    
    // --------------------------------------------------------------------

    public static function getInstance()
    {
       if( ! self::$instance instanceof self)
       {
           self::$instance = new self();
       } 
       
       return self::$instance;
    }
   
}

// END Session Class

/* End of file session.php */
/* Location: ./ob_modules/session/releases/0.0.1/session.php */