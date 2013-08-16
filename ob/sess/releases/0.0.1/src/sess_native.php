<?php
namespace Sess\Src;

/**
* Session Native Driver.
* 
* @author      Obullo Team.
* @version     0.1
*/
Class Sess_Native {
    
    public $db;
    public $encrypt_cookie       = false;
    public $expiration           = '7200';
    public $match_ip             = false;
    public $match_useragent      = true;
    public $cookie_name          = 'ob_session';
    public $cookie_prefix        = '';
    public $cookie_path          = '';
    public $cookie_domain        = '';
    public $time_to_update       = 300;
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
        log\me('debug', "Session Native Driver Initialized"); 
        
        foreach (array('encrypt_cookie','expiration', 'expire_on_close', 'match_ip', 
        'match_useragent', 'cookie_name', 'cookie_path', 'cookie_domain', 
        'time_to_update', 'time_reference', 'cookie_prefix', 'encryption_key') as $key)
        {
            $this->$key = (isset($params[$key])) ? $params[$key] : config($key, 'sess');
        }
        
        // @todo _unserialize func. use strip_slashes() func. We can add it later if we need it in Native Library. ?
                
        if($this->expire_on_close)
        {
            session_set_cookie_params(0);
            
            ini_set('session.gc_maxlifetime', '0');
            ini_set('session.cookie_lifetime', '0');  // 0
        } 
        else 
        {
            session_set_cookie_params($this->expiration, $this->cookie_path, $this->cookie_domain);
            
            // Configure garbage collection
            ini_set('session.gc_divisor', 100);
            ini_set('session.gc_maxlifetime', ($this->expiration == 0) ? 7200 : $this->expiration);
        }
        
        $this->now = $this->_getTime();

        session_name($this->cookie_prefix . $this->cookie_name);
        
        if( ! isset($_SESSION) ) // If another session_start() func is started before ?
        {
            session_start();
        }

        if (is_numeric($this->expiration))
        {
            if ($this->expiration > 0)
            {
                $this->sess_id_ttl = $this->expiration;
            }
            else
            {
                $this->sess_id_ttl = (60 * 60 * 24 * 365 * 2);
            }
        }

        // check if session id needs regeneration
        if ( $this->_sessionIdExpired() )
        {
            // regenerate session id (session data stays the
            // same, but old session storage is destroyed)
            $this->_sessionRegenerateId();
        }

        // delete old flashdata (from last request)
        $this->_flashdataSweep();

        // mark all new flashdata as old (data will be deleted before next request)
        $this->_flashdataMark();

        log\me('debug', "Session routines successfully run"); 

        return true;
    }
    
    // --------------------------------------------------------------------

    /**
     * Regenerate new session id.
     * 
     * @return void 
     */
    function _sessionRegenerateId()
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
        $_SESSION['regenerated'] = $this->_getTime();

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
            setcookie(session_name(), '', ($this->_getTime() - 42000), $this->cookie_path, $this->cookie_domain);
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
            return ( ! isset($_SESSION[$prefix.$item])) ? false : $_SESSION[$prefix.$item];
        }
    }
    
    // --------------------------------------------------------------------

    /**
    * Fetch all session data
    *
    * @access    public
    * @return    mixed
    */
    function allData()
    {
        return ( ! isset($_SESSION)) ? false : $_SESSION;
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
    function _sessionIdExpired()
    {
        if ( ! isset( $_SESSION['regenerated'] ) )
        {
            $_SESSION['regenerated'] = $this->_getTime();
            
            return false;
        }

        $expiry_time = time() - $this->sess_id_ttl;

        if ( $_SESSION['regenerated'] <=  $expiry_time )
        {
            return true;
        }

        return false;
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
    function setFlash($newdata = array(), $newval = '')  // ( obullo changes ... )
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
    function keepFlash($key) // ( obullo changes ...)
    {
        // 'old' flashdata gets removed.  Here we mark all 
        // flashdata as 'new' to preserve it from _flashdataSweep()
        // Note the function will return false if the $key 
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
    function getFlash($key, $prefix = '', $suffix = '')  // obullo changes ...
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
    * when _flashdataSweep() runs.
    *
    * @access    private
    * @return    void
    */
    function _flashdataMark()
    {
        $userdata = $this->allData();
        
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
    function _flashdataSweep()
    {              
        $userdata = $this->allData();
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
    function _getTime()
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