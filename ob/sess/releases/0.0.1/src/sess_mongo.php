<?php
namespace Ob\Sess\Src;

/**
* Session Mongodb Driver.
* 
* @author      Obullo Team.
* @version     0.1
*/
Class Sess_Mongo {
    
    public $db;
    public $sess_encrypt_cookie  = FALSE;
    public $sess_expiration      = '7200';
    public $sess_match_ip        = FALSE;
    public $sess_match_useragent = TRUE;
    public $sess_cookie_name     = 'ob_session';
    public $sess_db_var          = 'db';
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
    
    // --------------------------------------------------------------------

    function init($params = array())
    {
        \Ob\log\me('debug', "Session Database Driver Initialized"); 
        
        foreach (array('sess_db_var', 'sess_table_name', 'sess_encrypt_cookie',
        'sess_expiration', 'sess_die_cookie', 'sess_match_ip', 
        'sess_match_useragent', 'sess_cookie_name', 'cookie_path', 'cookie_domain', 
        'sess_time_to_update', 'time_reference', 'cookie_prefix', 'encryption_key') as $key)
        {
            $this->$key = (isset($params[$key])) ? $params[$key] : \Ob\config($key);
        }

        // _unserialize func. use strip_slashes() func.
        new \Ob\string\start();

        $this->now = $this->_get_time();

        // Set the expiration two years from now.
        if ($this->sess_expiration == 0)
        {
            $this->sess_expiration = (60 * 60 * 24 * 365 * 2);
        }

        // Set the cookie name
        $this->sess_cookie_name = $this->cookie_prefix . $this->sess_cookie_name;
        
        // Mongo Database Connection
        // --------------------------------------------------------------------
        
        $database = new \Ob\Db\Db(false);
        $this->db = $database->connect('db', array('dbdriver' => 'mongo'));
        
        // --------------------------------------------------------------------

        // Run the Session routine. If a session doesn't exist we'll 
        // create a new one.  If it does, we'll update it.
        if ( ! $this->_read())
        {
            $this->_create();
        }
        else
        {    
            $this->_update();
        }

        // Delete 'old' flashdata (from last request)
        $this->_flashdata_sweep();

        // Mark all new flashdata as old (data will be deleted before next request)
        $this->_flashdata_mark();

        // Delete expired sessions if necessary
        $this->_gc();

        \Ob\log\me('debug', "Session routines successfully run"); 

        return TRUE;
    }
    
    // --------------------------------------------------------------------

    /**
    * Fetch the current session data if it exists
    *
    * @access    public
    * @return    array() sessions.
    */
    function _read()
    {
        // Fetch the cookie
        $session = \Ob\i\cookie($this->sess_cookie_name);

        // No cookie?  Goodbye cruel world!...
        if ($session === FALSE)
        {               
            \Ob\log\me('debug', 'A session cookie was not found.');
            return FALSE;
        }
        
        // Decrypt the cookie data
        if ($this->sess_encrypt_cookie == TRUE)  // Obullo Changes "Encrypt Library Header redirect() Bug Fixed !"
        {
            $key     = \Ob\config('encryption_key');
            $session = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($session), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
        }
        else
        {    
            // encryption was not used, so we need to check the md5 hash
            $hash    = substr($session, strlen($session)-32); // get last 32 chars
            $session = substr($session, 0, strlen($session)-32);

            // Does the md5 hash match?  This is to prevent manipulation of session data in userspace
            if ($hash !==  md5($session . $this->encryption_key))
            {
                \Ob\log\me('error', 'The session cookie data did not match what was expected. This could be a possible hacking attempt.');
                $this->destroy();
                return FALSE;
            }
        }
        
        // Unserialize the session array
        $session = $this->_unserialize($session);
        
        // Is the session data we unserialized an array with the correct format?
        if ( ! is_array($session) OR ! isset($session['session_id']) 
        OR ! isset($session['ip_address']) OR ! isset($session['user_agent']) 
        OR ! isset($session['last_activity'])) 
        {               
            $this->destroy();
            return FALSE;
        }
        
        // Is the session current?
        if (($session['last_activity'] + $this->sess_expiration) < $this->now)
        {
            $this->destroy();
            return FALSE;
        }

        // Does the IP Match?
        if ($this->sess_match_ip == TRUE AND $session['ip_address'] != \Ob\i\ip_address())
        {
            $this->destroy();
            return FALSE;
        }
        
        // Does the User Agent Match?
        if ($this->sess_match_useragent == TRUE AND trim($session['user_agent']) != trim(substr(\Ob\i\user_agent(), 0, 50)))
        {
            $this->destroy();
            return FALSE;
        }
       
        // Db driver changes ...
        // -------------------------------------------------------------------- 
        
        $this->db->where('session_id', $session['session_id']);
                
        if ($this->sess_match_ip == TRUE)
        {
            $this->db->where('ip_address', $session['ip_address']);
        }

        if ($this->sess_match_useragent == TRUE)
        {
            $this->db->where('user_agent', $session['user_agent']);
        }
        
        $query = $this->db->get($this->sess_table_name);

        // Mongo db changes
        // -------------------------------------------------------------------- 
        
        // Is there custom data?  If so, add it to the main session array
        $row = $query->hasNext();
        
        // -------------------------------------------------------------------- 
        
        // No result?  Kill it!
        if ($row == FALSE)      // Obullo changes ..
        {
            $this->destroy();
            return FALSE;
        }  
        
        // Mongo db changes
        // -------------------------------------------------------------------- 
        
        $row = (object)$query->getNext();
        
        // -------------------------------------------------------------------- 
           
        if (isset($row->user_data) AND $row->user_data != '')
        {
            $custom_data = $this->_unserialize($row->user_data);

            if (is_array($custom_data))
            {
                foreach ($custom_data as $key => $val)
                {
                    $session[$key] = $val;
                }
            }
        }                

        // Session is valid!
        $this->userdata = $session;
        unset($session);
        
        return TRUE;
    }
    
    // --------------------------------------------------------------------

    /**
    * Write the session data
    *
    * @access    public
    * @return    void
    */
    function _write()
    {
        // set the custom userdata, the session data we will set in a second
        $custom_userdata = $this->userdata;
        $cookie_userdata = array();

        // Before continuing, we need to determine if there is any custom data to deal with.
        // Let's determine this by removing the default indexes to see if there's anything left in the array
        // and set the session data while we're at it
        foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
        {
            unset($custom_userdata[$val]);
            $cookie_userdata[$val] = $this->userdata[$val];
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
            $custom_userdata = $this->_serialize($custom_userdata);
        }

        // Run the update query
        $this->db->where('session_id', $this->userdata['session_id']);
        $this->db->update($this->sess_table_name, array('last_activity' => $this->userdata['last_activity'], 'user_data' => $custom_userdata));

        // Write the cookie.  Notice that we manually pass the cookie data array to the
        // _set_cookie() function. Normally that function will store $this->userdata, but 
        // in this case that array contains custom data, which we do not want in the cookie.
        $this->_set_cookie($cookie_userdata);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Create a new session
    *
    * @access    public
    * @return    void
    */
    function _create()
    {
        $sessid = '';
        while (strlen($sessid) < 32)
        {
            $sessid .= mt_rand(0, mt_getrandmax());
        }
        
        // To make the session ID even more secure we'll combine it with the user's IP
        $sessid .= \Ob\i\ip_address();

        $this->userdata = array(
                            'session_id'     => md5(uniqid($sessid, TRUE)),
                            'ip_address'     => \Ob\i\ip_address(),
                            'user_agent'     => substr(\Ob\i\user_agent(), 0, 50),
                            'last_activity'  => $this->now
                            );
        
        // Db driver changes..
        // --------------------------------------------------------------------  

        $this->db->insert($this->sess_table_name, $this->userdata);
        
        // Write the cookie        
        $this->_set_cookie(); 
    }
    
    // --------------------------------------------------------------------

    /**
    * Update an existing session
    *
    * @access    public
    * @return    void
    */
    function _update()
    {
        // We only update the session every five minutes by default
        if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now)
        {
            return;
        }

        // Save the old session id so we know which record to 
        // update in the database if we need it
        $old_sessid = $this->userdata['session_id'];
        $new_sessid = '';
        while (strlen($new_sessid) < 32)
        {
            $new_sessid .= mt_rand(0, mt_getrandmax());
        }
        
        // To make the session ID even more secure we'll combine it with the user's IP
        $new_sessid .= \Ob\i\ip_address();
        
        // Turn it into a hash
        $new_sessid = md5(uniqid($new_sessid, TRUE));
        
        // Update the session data in the session data array
        $this->userdata['session_id']    = $new_sessid;
        $this->userdata['last_activity'] = $this->now;
        
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
            $cookie_data[$val] = $this->userdata[$val];
        }

        $this->db->where('session_id', $old_sessid);
        $this->db->update($this->sess_table_name, array('last_activity' => $this->now, 'session_id' => $new_sessid)); 
        
        // Write the cookie
        $this->_set_cookie($cookie_data);
    }

    // --------------------------------------------------------------------

    /**
    * Destroy the current session
    *
    * @access    public
    * @return    void
    */
    function destroy()
    {
        // Db driver changes..
        // -------------------------------------------------------------------
        if(isset($this->userdata['session_id']))
        {
            // Kill the session DB row
            $this->db->where('session_id', $this->userdata['session_id']);
            $this->db->delete($this->sess_table_name);
        }
        // -------------------------------------------------------------------
        
        // Kill the cookie
        setcookie(           
                    $this->sess_cookie_name, 
                    addslashes(serialize(array())), 
                    ($this->now - 31500000), 
                    $this->cookie_path, 
                    $this->cookie_domain, 
                    FALSE
        );
    }
    
    // --------------------------------------------------------------------

    /**
    * Fetch a specific item from the session array
    *
    * @access   public
    * @param    string
    * @return   string
    */        
    function get($item, $prefix = '')
    {
        return ( ! isset($this->userdata[$prefix.$item])) ? FALSE : $this->userdata[$prefix.$item];
    }
    
    // --------------------------------------------------------------------

    /**
    * Fetch all session data
    *
    * @access    public
    * @return    mixed
    */
    function alldata()
    {
        return ( ! isset($this->userdata)) ? FALSE : $this->userdata;
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
    function set($newdata = array(), $newval = '', $prefix = '')
    {
        if (is_string($newdata))
        {
            $newdata = array($newdata => $newval);
        }

        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                $this->userdata[$prefix.$key] = $val;
            }
        }

        $this->_write();
    }

    // --------------------------------------------------------------------

    /**
    * Delete a session variable from the "userdata" array
    *
    * @access    array
    * @return    void
    */       
    function remove($newdata = array(), $prefix = '')
    {
        if (is_string($newdata))
        {
            $newdata = array($newdata => '');
        }

        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                unset($this->userdata[$prefix.$key]);
            }
        }

        $this->_write();
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
    function set_flash($newdata = array(), $newval = '')  // ( obullo changes ... )
    {
        if (is_string($newdata))
        {
            $newdata = array($newdata => $newval);
        }
        
        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                $flashdata_key = $this->flashdata_key.':new:'.$key;
                $this->set($flashdata_key, $val);
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
    function keep_flash($key) // ( obullo changes ...)
    {
        // 'old' flashdata gets removed.  Here we mark all 
        // flashdata as 'new' to preserve it from _flashdata_sweep()
        // Note the function will return FALSE if the $key 
        // provided cannot be found
        $old_flashdata_key = $this->flashdata_key.':old:'.$key;
        $value = $this->get($old_flashdata_key);

        $new_flashdata_key = $this->flashdata_key.':new:'.$key;
        $this->set($new_flashdata_key, $value);
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
    function get_flash($key, $prefix = '', $suffix = '')  // obullo changes ...
    {
        $flashdata_key = $this->flashdata_key.':old:'.$key;
        
        $value = $this->get($flashdata_key);
        
        if($value == '')
        {
            $prefix = '';
            $suffix = '';
        }
        
        return $prefix.$value.$suffix;
    }

    // ------------------------------------------------------------------------

    /**
    *  Alias of sess_get_flash. 
    */
    function flash($key, $prefix = '', $suffix = '')
    {
        return get_flash($key, $prefix, $suffix);
    }

    // ------------------------------------------------------------------------

    /**
    * Identifies flashdata as 'old' for removal
    * when _flashdata_sweep() runs.
    *
    * @access    private
    * @return    void
    */
    function _flashdata_mark()
    {
        $userdata = $this->alldata();
        
        foreach ($userdata as $name => $value)
        {
            $parts = explode(':new:', $name);
            if (is_array($parts) && count($parts) === 2)
            {
                $new_name = $this->flashdata_key.':old:'.$parts[1];
                
                $this->set($new_name, $value);
                $this->remove($name);
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
    function _flashdata_sweep()
    {              
        $userdata = $this->alldata();
        foreach ($userdata as $key => $value)
        {
            if (strpos($key, ':old:'))
            {
                $this->remove($key);
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
    function _get_time()
    {
        $time = time();
        if (strtolower($this->time_reference) == 'gmt')
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
    
    // --------------------------------------------------------------------

    /**
    * Write the session cookie
    *
    * @access    public
    * @return    void
    */
    function _set_cookie($cookie_data = NULL)
    {
        if (is_null($cookie_data))
        {
            $cookie_data = $this->userdata;
        }

        // Serialize the userdata for the cookie
        $cookie_data = $this->_serialize($cookie_data);
        
        if ($this->sess_encrypt_cookie == TRUE) // Obullo Changes "Encrypt Library Header redirect() Bug Fixed !"
        {
            $key         = \Ob\config('encryption_key');
            $cookie_data = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cookie_data, MCRYPT_MODE_CBC, md5(md5($key))));
        }
        else
        {
            // if encryption is not used, we provide an md5 hash to prevent userside tampering
            $cookie_data = $cookie_data . md5($cookie_data . $this->encryption_key);
        }
        
        // ( Obullo Changes .. set cookie life time 0 )
        $expiration = (\Ob\config('sess_die_cookie')) ? 0 : $this->sess_expiration + time();

        // Set the cookie
        setcookie(
                    $this->sess_cookie_name,
                    $cookie_data,
                    $expiration,
                    $this->cookie_path,
                    $this->cookie_domain,
                    0
                );
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
    function _serialize($data)
    {
        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                if (is_string($val))
                {
                    $data[$key] = str_replace('\\', '{{slash}}', $val);
                }
            }
        }
        else
        {
            if (is_string($val))
            {
                $data = str_replace('\\', '{{slash}}', $data);
            }
        }
        
        return serialize($data);
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
    function _unserialize($data)
    {
        $data = @unserialize(\Ob\string\strip_slashes($data));
        
        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                if(is_string($val))
                {
                    $data[$key] = str_replace('{{slash}}', '\\', $val);
                }
            }
            
            return $data;
        }
        
        return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
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
    function _gc()
    {
        srand(time());
        
        if ((rand() % 100) < $this->gc_probability)
        {
            $expire = $this->now - $this->sess_expiration;
            
            $this->db->where("last_activity < {$expire}");
            $this->db->delete($this->sess_table_name);

            \Ob\log\me('debug', 'Session garbage collection performed.');
        }
    }
    
}

/* End of file sess_mongo.php */
/* Location: ./ob/sess/releases/0.0.1/src/sess_mongo.php */