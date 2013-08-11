<?php
namespace Ob\Sess\Src;

/**
* Session Native Driver.
* 
* @author      Obullo Team.
* @version     0.1
*/
Class Sess_Native {
    
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
    
    // --------------------------------------------------------------------

    function init($params = array())
    {
        \Ob\log\me('debug', "Session Native Driver Initialized"); 
        
        foreach (array('sess_encrypt_cookie','sess_expiration', 'sess_die_cookie', 'sess_match_ip', 
        'sess_match_useragent', 'sess_cookie_name', 'cookie_path', 'cookie_domain', 
        'sess_time_to_update', 'time_reference', 'cookie_prefix', 'encryption_key') as $key)
        {
            $this->$key = (isset($params[$key])) ? $params[$key] : \Ob\config($key);
        }
        
        // @todo _unserialize func. use strip_slashes() func. We can add it later if we need it in Native Library. ?
                
        if($this->sess_die_cookie)
        {
            session_set_cookie_params(0);
            
            ini_set('session.gc_maxlifetime', '0');
            ini_set('session.cookie_lifetime', '0');  // 0
        } 
        else 
        {
            session_set_cookie_params($this->sess_expiration, $this->cookie_path, $this->cookie_domain);
            
            // Configure garbage collection
            ini_set('session.gc_divisor', 100);
            ini_set('session.gc_maxlifetime', ($this->sess_expiration == 0) ? 7200 : $this->sess_expiration);
        }
        
        $this->now = $this->_get_time();

        session_name($this->cookie_prefix . $this->sess_cookie_name);
        
        if( ! isset($_SESSION) ) // If another session_start() func is started before ?
        {
            session_start();
        }

        if (is_numeric($this->sess_expiration))
        {
            if ($this->sess_expiration > 0)
            {
                $this->sess_id_ttl = $this->sess_expiration;
            }
            else
            {
                $this->sess_id_ttl = (60 * 60 * 24 * 365 * 2);
            }
        }

        // check if session id needs regeneration
        if ( $this->_session_id_expired() )
        {
            // regenerate session id (session data stays the
            // same, but old session storage is destroyed)
            $this->_session_regenerate_id();
        }

        // delete old flashdata (from last request)
        $this->_flashdata_sweep();

        // mark all new flashdata as old (data will be deleted before next request)
        $this->_flashdata_mark();

        \Ob\log\me('debug', "Session routines successfully run"); 

        return TRUE;
    }
    
    // --------------------------------------------------------------------

    /**
     * Regenerate new session id.
     * 
     * @return void 
     */
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
        $_SESSION['regenerated'] = $this->_get_time();

        // end the current session and store session data.
        session_write_close();
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
        unset($_SESSION);
        
        if ( isset( $_COOKIE[session_name()] ) )
        {
            setcookie(session_name(), '', ($this->_get_time() - 42000), $this->cookie_path, $this->cookie_domain);
        }
        
        session_destroy();
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
        if($item == 'session_id') 
        { 
            return session_id();
        }
        else
        {
            return ( ! isset($_SESSION[$prefix.$item])) ? FALSE : $_SESSION[$prefix.$item];
        }
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
        return ( ! isset($_SESSION)) ? FALSE : $_SESSION;
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
                $_SESSION[$prefix.$key] = $val;
            }
        }
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
                unset($_SESSION[$prefix.$key]);
            }
        }
    }

    // ------------------------------------------------------------------------
    
    /**
     * Check session id is expired
     * 
     * @return boolean 
     */
    function _session_id_expired()
    {
        if ( ! isset( $_SESSION['regenerated'] ) )
        {
            $_SESSION['regenerated'] = $this->_get_time();
            
            return FALSE;
        }

        $expiry_time = time() - $this->sess_id_ttl;

        if ( $_SESSION['regenerated'] <=  $expiry_time )
        {
            return TRUE;
        }

        return FALSE;
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
    
}

/* End of file sess_native.php */
/* Location: ./ob/sess/releases/0.0.1/src/sess_native.php */