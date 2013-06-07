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
        log_me('debug', "Session Database Driver Initialized"); 

        $sess   = lib('ob/Session');
        $config = lib('ob/Config');
        
        foreach (array('sess_encrypt_cookie', 'sess_driver', 'sess_db_var', 'sess_table_name', 
        'sess_expiration', 'sess_die_cookie', 'sess_match_ip', 'sess_match_useragent', 'sess_cookie_name', 'cookie_path', 
        'cookie_domain', 'sess_time_to_update', 'time_reference', 'cookie_prefix', 'encryption_key') as $key)
        {
            $sess->{$key} = (isset($params[$key])) ? $params[$key] : $config->item($key);
        }

        // _unserialize func. use strip_slashes() func.
        loader::helper('ob/string');

        $sess->now = _get_time();

        // Set the expiration two years from now.
        if ($sess->sess_expiration == 0)
        {
            $sess->sess_expiration = (60 * 60 * 24 * 365 * 2);
        }

        // Set the cookie name
        $sess->sess_cookie_name = $sess->cookie_prefix . $sess->sess_cookie_name;
        
        // Database settings
        // --------------------------------------------------------------------

        $params = array();
        $params['variable'] = $sess->sess_db_var;
        $params['hostname'] = db_item('hostname', $sess->sess_db_var);
        $params['username'] = db_item('username', $sess->sess_db_var);
        $params['password'] = db_item('password', $sess->sess_db_var);
        $params['database'] = db_item('database', $sess->sess_db_var);
        $params['dbdriver'] = db_item('dbdriver', $sess->sess_db_var);
        $params['dbprefix'] = db_item('dbprefix', $sess->sess_db_var);
        $params['swap_pre'] = db_item('swap_pre', $sess->sess_db_var);
        $params['dbh_port'] = db_item('dbh_port', $sess->sess_db_var);
        $params['charset']  = db_item('charset', $sess->sess_db_var);
        
        if(db_item('dsn', $sess->sess_db_var) != '')
        {
            $params['dsn']  = db_item('dsn', $sess->sess_db_var);
        }
        
        $params['options']  = db_item('options', $sess->sess_db_var);
        
        $sess->db = loader::database($params, TRUE);
        
        // --------------------------------------------------------------------
        
        // Run the Session routine. If a session doesn't exist we'll 
        // create a new one.  If it does, we'll update it.
        if ( ! sess_read())
        {
            sess_create();
        }
        else
        {    
            sess_update();
        }

        // Delete 'old' flashdata (from last request)
        _flashdata_sweep();

        // Mark all new flashdata as old (data will be deleted before next request)
        _flashdata_mark();

        // Delete expired sessions if necessary
        _sess_gc();

        log_me('debug', "Session routines successfully run"); 

        return TRUE;
    }
}
// --------------------------------------------------------------------

/**
* Fetch the current session data if it exists
*
* @access    public
* @return    array() sessions.
*/
if( ! function_exists('sess_read') ) 
{
    function sess_read()
    {
        $sess = lib('ob/Session');
        
        // Fetch the cookie
        $session = i_cookie($sess->sess_cookie_name);

        // No cookie?  Goodbye cruel world!...
        if ($session === FALSE)
        {               
            log_me('debug', 'A session cookie was not found.');
            
            return FALSE;
        }
        
        // Decrypt the cookie data
        if ($sess->sess_encrypt_cookie == TRUE)  // Obullo Changes "Encrypt Library Header redirect() Bug Fixed !"
        {
            $key     = config('encryption_key');
            $session = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($session), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
        }
        else
        {    
            // encryption was not used, so we need to check the md5 hash
            $hash    = substr($session, strlen($session)-32); // get last 32 chars
            $session = substr($session, 0, strlen($session)-32);

            // Does the md5 hash match?  This is to prevent manipulation of session data in userspace
            if ($hash !==  md5($session . $sess->encryption_key))
            {
                log_me('error', 'The session cookie data did not match what was expected. This could be a possible hacking attempt.');
                
                sess_destroy();
                return FALSE;
            }
        }
        
        // Unserialize the session array
        $session = _unserialize($session);
        
        // Is the session data we unserialized an array with the correct format?
        if ( ! is_array($session) OR ! isset($session['session_id']) 
        OR ! isset($session['ip_address']) OR ! isset($session['user_agent']) 
        OR ! isset($session['last_activity'])) 
        {               
            sess_destroy();
            return FALSE;
        }
        
        // Is the session current?
        if (($session['last_activity'] + $sess->sess_expiration) < $sess->now)
        {
            sess_destroy();
            return FALSE;
        }

        // Does the IP Match?
        if ($sess->sess_match_ip == TRUE AND $session['ip_address'] != i_ip_address())
        {
            sess_destroy();
            return FALSE;
        }
        
        // Does the User Agent Match?
        if ($sess->sess_match_useragent == TRUE AND trim($session['user_agent']) != trim(substr(i_user_agent(), 0, 50)))
        {
            sess_destroy();
            return FALSE;
        }
        
        // Db driver changes ...
        // -------------------------------------------------------------------- 
        
        $sess->db->where('session_id', $session['session_id']);
                
        if ($sess->sess_match_ip == TRUE)
        {
            $sess->db->where('ip_address', $session['ip_address']);
        }

        if ($sess->sess_match_useragent == TRUE)
        {
            $sess->db->where('user_agent', $session['user_agent']);
        }
        
        $query = $sess->db->get($sess->sess_table_name);

        // Is there custom data?  If so, add it to the main session array
        $row = $query->row();
        
        // No result?  Kill it!
        if ($row == FALSE)      // Obullo changes ..
        {
            sess_destroy();
            return FALSE;
        }  
           
        if (isset($row->user_data) AND $row->user_data != '')
        {
            $custom_data = _unserialize($row->user_data);

            if (is_array($custom_data))
            {
                foreach ($custom_data as $key => $val)
                {
                    $session[$key] = $val;
                }
            }
        }                

        // Session is valid!
        $sess->userdata = $session;
        unset($session);
        
        return TRUE;
    }
}
// --------------------------------------------------------------------

/**
* Write the session data
*
* @access    public
* @return    void
*/
if( ! function_exists('sess_write') ) 
{
    function sess_write()
    {
        $sess = lib('ob/Session');
        
        // set the custom userdata, the session data we will set in a second
        $custom_userdata = $sess->userdata;
        $cookie_userdata = array();

        // Before continuing, we need to determine if there is any custom data to deal with.
        // Let's determine this by removing the default indexes to see if there's anything left in the array
        // and set the session data while we're at it
        foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
        {
            unset($custom_userdata[$val]);
            $cookie_userdata[$val] = $sess->userdata[$val];
        }

        // Did we find any custom data?  If not, we turn the empty array into a string
        // since there's no reason to serialize and store an empty array in the DB
        if (count($custom_userdata) === 0)
        {
            $custom_userdata = '';
        }
        else
        {    
            // Serialize the custom data array so we can store it
            $custom_userdata = _serialize($custom_userdata);
        }

        // Run the update query
        $sess->db->where('session_id', $sess->userdata['session_id']);
        $sess->db->update($sess->sess_table_name, array('last_activity' => $sess->userdata['last_activity'], 
        'user_data' => $custom_userdata));

        // Write the cookie.  Notice that we manually pass the cookie data array to the
        // _set_cookie() function. Normally that function will store $this->userdata, but 
        // in this case that array contains custom data, which we do not want in the cookie.
        _set_cookie($cookie_userdata);
    }
}

/**
* Create a new session
*
* @access    public
* @return    void
*/
if( ! function_exists('sess_create') ) 
{
    function sess_create()
    {    
        $sess = lib('ob/Session');
        
        $sessid = '';
        while (strlen($sessid) < 32)
        {
            $sessid .= mt_rand(0, mt_getrandmax());
        }
        
        // To make the session ID even more secure we'll combine it with the user's IP
        $sessid .= i_ip_address();

             
        $sess->userdata = array(
                            'session_id'     => md5(uniqid($sessid, TRUE)),
                            'ip_address'     => i_ip_address(),
                            'user_agent'     => substr(i_user_agent(), 0, 50),
                            'last_activity'  => $sess->now
                            );
        
        // Db driver changes..
        // --------------------------------------------------------------------  

        $sess->db->insert($sess->sess_table_name, $sess->userdata);
        
        // Write the cookie        
        _set_cookie(); 
    }
}

// --------------------------------------------------------------------

/**
* Update an existing session
*
* @access    public
* @return    void
*/
if( ! function_exists('sess_update') ) 
{
    function sess_update()
    {
        $sess = lib('ob/Session');
        
        // We only update the session every five minutes by default
        if (($sess->userdata['last_activity'] + $sess->sess_time_to_update) >= $sess->now)
        {
            return;
        }

        // Save the old session id so we know which record to 
        // update in the database if we need it
        $old_sessid = $sess->userdata['session_id'];
        $new_sessid = '';
        while (strlen($new_sessid) < 32)
        {
            $new_sessid .= mt_rand(0, mt_getrandmax());
        }
        
        // To make the session ID even more secure we'll combine it with the user's IP
        $new_sessid .= i_ip_address();
        
        // Turn it into a hash
        $new_sessid = md5(uniqid($new_sessid, TRUE));
        
        // Update the session data in the session data array
        $sess->userdata['session_id']    = $new_sessid;
        $sess->userdata['last_activity'] = $sess->now;
        
        // _set_cookie() will handle this for us if we aren't using database sessions
        // by pushing all userdata to the cookie.
        $cookie_data = NULL;
        
        // Db driver changes..
        // -------------------------------------------------------------------
        
        // Update the session ID and last_activity field in the DB if needed

        // set cookie explicitly to only have our session data
        $cookie_data = array();
        foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
        {
            $cookie_data[$val] = $sess->userdata[$val];
        }

        $sess->db->where('session_id', $old_sessid);
        $sess->db->update($sess->sess_table_name, 
        array('last_activity' => $sess->now, 'session_id' => $new_sessid)); 
        
        // Write the cookie
        _set_cookie($cookie_data);
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
        
        // Db driver changes..
        // -------------------------------------------------------------------
        if(isset($sess->userdata['session_id']))
        {
            // Kill the session DB row
            $sess->db->where('session_id', $sess->userdata['session_id']);
            $sess->db->delete($sess->sess_table_name);
        }
        // -------------------------------------------------------------------
        
        // Kill the cookie
        setcookie(           
                    $sess->sess_cookie_name, 
                    addslashes(serialize(array())), 
                    ($sess->now - 31500000), 
                    $sess->cookie_path, 
                    $sess->cookie_domain, 
                    FALSE
        );
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
        $sess = lib('ob/Session');
        
        return ( ! isset($sess->userdata[$prefix.$item])) ? FALSE : $sess->userdata[$prefix.$item];
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
        $sess = lib('ob/Session');
        
        return ( ! isset($sess->userdata)) ? FALSE : $sess->userdata;
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
        $sess = lib('ob/Session');
        
        if (is_string($newdata))
        {
            $newdata = array($newdata => $newval);
        }

        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                $sess->userdata[$prefix.$key] = $val;
            }
        }

        sess_write();
    }
}
// --------------------------------------------------------------------

/**
* Delete a session variable from the "userdata" array
*
* @access    array
* @return    void
*/
if( ! function_exists('sess_unset') ) 
{
    function sess_unset($newdata = array(), $prefix = '')  // ( obullo changes ... )
    {
        $sess = lib('ob/Session');
        
        if (is_string($newdata))
        {
            $newdata = array($newdata => '');
        }

        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                unset($sess->userdata[$prefix.$key]);
            }
        }

        sess_write();
    }
}
// ------------------------------------------------------------------------

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
    function sess_set_flash($newdata = array(), $newval = '') // ( obullo changes ...)
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
    function sess_get_flash($key, $prefix = '', $suffix = '')  // ( obullo changes ...)
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

/**
* Write the session cookie
*
* @access    public
* @return    void
*/
if( ! function_exists('_set_cookie') ) 
{
    function _set_cookie($cookie_data = NULL)
    {
        $sess = lib('ob/Session');
        
        if (is_null($cookie_data))
        {
            $cookie_data = $sess->userdata;
        }

        // Serialize the userdata for the cookie
        $cookie_data = _serialize($cookie_data);
        
        if ($sess->sess_encrypt_cookie == TRUE) // Obullo Changes "Encrypt Library Header redirect() Bug Fixed !"
        {
            $key         = config('encryption_key');
            $cookie_data = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cookie_data, MCRYPT_MODE_CBC, md5(md5($key))));
        }
        else
        {
            // if encryption is not used, we provide an md5 hash to prevent userside tampering
            $cookie_data = $cookie_data . md5($cookie_data . $sess->encryption_key);
        }
        
        // ( Obullo Changes .. set cookie life time 0 )
        $expiration = ($sess->sess_die_cookie) ? 0 : $sess->sess_expiration + time();
        
        // Set the cookie
        setcookie(
                    $sess->sess_cookie_name,
                    $cookie_data,
                    $expiration,
                    $sess->cookie_path,
                    $sess->cookie_domain,
                    0
                );
    }
}
// --------------------------------------------------------------------

/**
* Serialize an array
*
* This function first converts any slashes found in the array to a temporary
* marker, so when it gets unserialized the slashes will be preserved
*
* @access   private
* @param    array
* @return   string
*/
if( ! function_exists('_serialize') ) 
{
    function _serialize($data)
    {
        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                if (is_string($val))
                $data[$key] = str_replace('\\', '{{slash}}', $val);
            }
        }
        else
        {
            if (is_string($val))
            $data = str_replace('\\', '{{slash}}', $data);
        }
        
        return serialize($data);
    }
}
// --------------------------------------------------------------------

/**
* Unserialize
*
* This function unserializes a data string, then converts any
* temporary slash markers back to actual slashes
*
* @access    private
* @param    array
* @return    string
*/
if( ! function_exists('_unserialize') ) 
{
    function _unserialize($data)
    {
        $data = @unserialize(strip_slashes($data));
        
        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                if(is_string($val))
                $data[$key] = str_replace('{{slash}}', '\\', $val);
            }
            
            return $data;
        }
        
        return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
    }
}
// --------------------------------------------------------------------

/**
* Garbage collection
*
* This deletes expired session rows from database
* if the probability percentage is met
*
* @access    public
* @return    void
*/
if( ! function_exists('_sess_gc') ) 
{
    function _sess_gc()
    {
        $sess = lib('ob/Session');
        
        srand(time());
        
        if ((rand() % 100) < $sess->gc_probability)
        {
            $expire = $sess->now - $sess->sess_expiration;
            
            $sess->db->where("last_activity < {$expire}");
            $sess->db->delete($sess->sess_table_name);

            log_me('debug', 'Session garbage collection performed.');
        }
    }
}

/* End of file session_database.php */
/* Location: ./obullo/helpers/drivers/session/session_database.php */