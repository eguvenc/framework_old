<?php 
defined('BASE') or exit('Access Denied!');
 
/**
* Obullo Framework (c) 2009 - 2012.
* Procedural Session Implementation With Session Class. 
* Less coding, and More Control.
* 
* @author      Obullo Team
* 
*/

/**
* @see Chapter / Helpers / Session Helper
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
            $config = lib('ob/Config');
            $driver = (isset($params['sess_driver'])) ? $params['sess_driver'] : $config->item('sess_driver');
            
            loader::helper('core/driver');
                                            
            $helpername = 'session_'.$driver; // Driver name
            
            helper_driver('session', $helpername, $params);
            
            _sess_start($params); // Start the sessions
            
            $session_start = TRUE;
            
            return TRUE;
        }
        
        log_me('debug', "Sessions started"); 
        
        return FALSE;
    }
}

/* End of file session.php */
/* Location: ./obullo/helpers/session.php */