<?php 
defined('BASE') or exit('Access Denied!');

/**
* Obullo Framework (c) 2010.
* Procedural Session Implementation With Session Class. 
* Less coding and More Control.
* 
* @author      Obullo Team.
* @version     0.1
* @version     0.2 added extend support
* @version     0.3 added config('sess_die_cookie') and sess() func.
*/
if( ! function_exists('_sess_start') ) 
{
    function _sess_start($params = array())
    {                       
        log_me('debug', "Session Native Driver Initialized"); 

        $sess   = lib('ob/Session');
        $config = lib('ob/Config');

        foreach (array('sess_expiration', 'sess_match_ip', 'sess_die_cookie',
        'sess_match_useragent', 'sess_cookie_name', 'cookie_path', 'cookie_domain', 
        'sess_time_to_update', 'time_reference', 'cookie_prefix') as $key)
        {
            $sess->{$key} = (isset($params[$key])) ? $params[$key] : $config->item($key);
        }

        // @todo _unserialize func. use strip_slashes() func. We can add it later if we need it in Native Library. ?
        // loader::helper('ob/string');
                
        if($sess->sess_die_cookie)
        {
            session_set_cookie_params(0);
            
            ini_set('session.gc_maxlifetime', '0');
            ini_set('session.cookie_lifetime', '0');  // 0
        } 
        else 
        {
            session_set_cookie_params($sess->sess_expiration, $sess->cookie_path, $sess->cookie_domain);
            
            // Configure garbage collection
            ini_set('session.gc_divisor', 100);
            ini_set('session.gc_maxlifetime', ($sess->sess_expiration == 0) ? 7200 : $sess->sess_expiration);
        }
        
        $sess->now = _get_time();

        session_name($sess->cookie_prefix . $sess->sess_cookie_name);
        
        if( ! isset($_SESSION) ) // If another session_start() func is started before ?
        {
            session_start();
        }

        if (is_numeric($sess->sess_expiration))
        {
            if ($sess->sess_expiration > 0)
            {
                $sess->sess_id_ttl = $sess->sess_expiration;
            }
            else
            {
                $sess->sess_id_ttl = (60 * 60 * 24 * 365 * 2);
            }
        }

        // check if session id needs regeneration
        if ( _session_id_expired() )
        {
            // regenerate session id (session data stays the
            // same, but old session storage is destroyed)
            _session_regenerate_id();
        }

        // delete old flashdata (from last request)
        _flashdata_sweep();

        // mark all new flashdata as old (data will be deleted before next request)
        _flashdata_mark();

        log_me('debug', "Session routines successfully run"); 

        return TRUE;
    }
}

// --------------------------------------------------------------------

if( ! function_exists('_session_regenerate_id') ) 
{
    function _session_regenerate_id()
    {
        // copy old session data, including its id
        $old_session_id = session_id();
        $old_session_data = $_SESSION;

        // regenerate session id and store it
        session_regenerate_id();
        $new_session_id = session_id();

        // switch to the old session and destroy its storage
        session_id($old_session_id);
        session_destroy();

        // switch back to the new session id and send the cookie
        session_id($new_session_id);
        session_start();

        // restore the old session data into the new session
        $_SESSION = $old_session_data;

        // update the session creation time
        $_SESSION['regenerated'] = _get_time();

        // end the current session and store session data.
        session_write_close();
    }

}

// --------------------------------------------------------------------

/**
* Destroy the current session
*
* @access    public
* @return    void
*/
if( ! function_exists('sess_destroy') ) 
{
    function sess_destroy()
    {   
        $sess = lib('ob/Session');
        
        unset($_SESSION);
        
        if ( isset( $_COOKIE[session_name()] ) )
        {
            setcookie(session_name(), '', (_get_time() - 42000), $sess->cookie_path, $sess->cookie_domain);
        }
        
        session_destroy();
    }
}
// --------------------------------------------------------------------

/**
* Fetch a specific item from the session array
*
* @access   public
* @param    string
* @return   string
*/        
if( ! function_exists('sess_get') ) 
{
    function sess_get($item, $prefix = '')
    {
        if($item == 'session_id') 
        { 
            return session_id();
        }
        else
        {
            return ( ! isset($_SESSION[$prefix.$item])) ? FALSE : $_SESSION[$prefix.$item];
        }
    }
}
// --------------------------------------------------------------------

/**
* Alias of sess_get(); function.
*
* @access   public
* @param    string
* @return   string
*/
if( ! function_exists('sess') ) 
{
    function sess($item, $prefix = '')
    {
        return sess_get($prefix.$item);
    }
}
// --------------------------------------------------------------------

/**
* Fetch all session data
*
* @access    public
* @return    mixed
*/
if( ! function_exists('sess_alldata') ) 
{
    function sess_alldata()
    {
        return ( ! isset($_SESSION)) ? FALSE : $_SESSION;
    }
}
// --------------------------------------------------------------------

/**
* Add or change data in the "userdata" array
*
* @access   public
* @param    mixed
* @param    string
* @return   void
*/       
if( ! function_exists('sess_set') ) 
{ 
    function sess_set($newdata = array(), $newval = '', $prefix = '')
    {
        if (is_string($newdata))
        {
            $newdata = array($newdata => $newval);
        }

        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                $_SESSION[$prefix.$key] = $val;
            }
        }
    }
}
// --------------------------------------------------------------------

/**
* Delete a session variable from the "userdata" array
*
* @access    public
* @param     array()
* @return    void
*/       
if( ! function_exists('sess_unset') ) 
{ 
    function sess_unset($newdata = array(), $prefix = '')  // obullo changes ...
    {
        if (is_string($newdata))
        {
            $newdata = array($newdata => '');
        }

        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                unset($_SESSION[$prefix.$key]);
            }
        }
    }
}
// ------------------------------------------------------------------------

/**
* Checks if session has expired
* @access    private
*/
if( ! function_exists('_session_id_expired') ) 
{ 
    function _session_id_expired()
    {
        $sess = lib('ob/Session');
        
        if ( ! isset( $_SESSION['regenerated'] ) )
        {
            $_SESSION['regenerated'] = _get_time();
            return FALSE;
        }

        $expiry_time = time() - $sess->sess_id_ttl;

        if ( $_SESSION['regenerated'] <=  $expiry_time )
        {
            return TRUE;
        }

        return FALSE;
    }
}
    
/**
* Add or change flashdata, only available
* until the next request
*
* @access   public
* @param    mixed
* @param    string
* @return   void
*/
if( ! function_exists('sess_set_flash') ) 
{ 
    function sess_set_flash($newdata = array(), $newval = '')  // ( obullo changes ... )
    {
        $sess = lib('ob/Session');
        
        if (is_string($newdata))
        {
            $newdata = array($newdata => $newval);
        }
        
        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                $flashdata_key = $sess->flashdata_key.':new:'.$key;
                sess_set($flashdata_key, $val);
            }
        }
    } 
}
// ------------------------------------------------------------------------

/**
* Keeps existing flashdata available to next request.
*
* @access   public
* @param    string
* @return   void
*/
if( ! function_exists('sess_keep_flash') ) 
{ 
    function sess_keep_flash($key) // ( obullo changes ...)
    {
        $sess = lib('ob/Session');
        
        // 'old' flashdata gets removed.  Here we mark all 
        // flashdata as 'new' to preserve it from _flashdata_sweep()
        // Note the function will return FALSE if the $key 
        // provided cannot be found
        $old_flashdata_key = $sess->flashdata_key.':old:'.$key;
        $value = sess_get($old_flashdata_key);

        $new_flashdata_key = $sess->flashdata_key.':new:'.$key;
        sess_set($new_flashdata_key, $value);
    }
}
// ------------------------------------------------------------------------

/**
* Fetch a specific flashdata item from the session array
*
* @access   public
* @param    string  $key you want to fetch
* @param    string  $prefix html open tag
* @param    string  $suffix html close tag
* 
* @version  0.1
* @version  0.2     added prefix and suffix parameters.
* 
* @return   string
*/
if( ! function_exists('sess_get_flash') ) 
{ 
    function sess_get_flash($key, $prefix = '', $suffix = '')  // obullo changes ...
    {
        $sess = lib('ob/Session');
        
        $flashdata_key = $sess->flashdata_key.':old:'.$key;
        
        $value = sess_get($flashdata_key);
        
        if($value == '')
        {
            $prefix = '';
            $suffix = '';
        }
        
        return $prefix.$value.$suffix;
    }
}

// ------------------------------------------------------------------------

/**
*  Alias of sess_get_flash. 
*/
if( ! function_exists('sess_flash'))
{
    function sess_flash($key, $prefix = '', $suffix = '')
    {
        return sess_get_flash($key, $prefix, $suffix);
    }
}

// ------------------------------------------------------------------------

/**
* Identifies flashdata as 'old' for removal
* when _flashdata_sweep() runs.
*
* @access    private
* @return    void
*/
if( ! function_exists('_flashdata_mark') ) 
{ 
    function _flashdata_mark()
    {
        $sess = lib('ob/Session');
        
        $userdata = sess_alldata();
        foreach ($userdata as $name => $value)
        {
            $parts = explode(':new:', $name);
            if (is_array($parts) && count($parts) === 2)
            {
                $new_name = $sess->flashdata_key.':old:'.$parts[1];
                sess_set($new_name, $value);
                sess_unset($name);
            }
        }
    }
}
// ------------------------------------------------------------------------

/**
* Removes all flashdata marked as 'old'
*
* @access    private
* @return    void
*/
if( ! function_exists('_flashdata_sweep') ) 
{
    function _flashdata_sweep()
    {              
        $userdata = sess_alldata();
        foreach ($userdata as $key => $value)
        {
            if (strpos($key, ':old:'))
            {
                sess_unset($key);
            }
        }
    }
}
// --------------------------------------------------------------------

/**
* Get the "now" time
*
* @access    private
* @return    string
*/
if( ! function_exists('_get_time') ) 
{
    function _get_time()
    {   
        $sess = lib('ob/Session');
        
        $time = time();
        if (strtolower($sess->time_reference) == 'gmt')
        {
            $now  = time();
            $time = mktime( gmdate("H", $now), 
            gmdate("i", $now), 
            gmdate("s", $now), 
            gmdate("m", $now), 
            gmdate("d", $now), 
            gmdate("Y", $now)
            );
        }
        return $time;
    }
}
// --------------------------------------------------------------------


/* End of file session_native.php */
/* Location: ./obullo/helpers/drivers/session/session_native.php */
