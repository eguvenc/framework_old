<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009 - 2012.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @copyright       Obullo Team
 * @filesource
 * @license
 */
 
// ------------------------------------------------------------------------

/**
 * Session Class
 *
 * @package	Obullo
 * @subpackage	Libraries
 * @category	Sessions
 * @author      Obullo Team
 * @link	
 */

Class OB_Session {
    
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
   
}

// END Session Class

/* End of file Session.php */
/* Location: ./obullo/libraries/Session.php */