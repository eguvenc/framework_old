<?php
namespace Ob;
    
Class Sess_Cookie {

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
    
    function start()
    {
        
    }
    
    function set()
    {
        echo 'ok';
    }
}
