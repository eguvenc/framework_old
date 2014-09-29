<?php

/**
 * Session Native Driver
 *
 * @package       packages
 * @subpackage    sess_native
 * @category      sessions
 * @link
 */
Class Sess_Native
{
    public $request;
    public $now;
    public $encrypt_cookie = false;
    public $regenerate_id  = false;
    public $expiration = '7200'; // two hours
    public $match_ip = false;
    public $match_useragent = true;
    public $cookie_name = 'frm_session';
    public $cookie_prefix = '';
    public $cookie_path = '';
    public $cookie_domain = '';
    public $time_to_update = 300;
    public $encryption_key = '';
    public $flashdata_key = 'flash';
    public $time_reference = 'time';
    public $config = array();  // php ini key => values

    // --------------------------------------------------------------------

    /**
     * Set php.ini settings and configuration variables
     *
     * @uses ini_set()
     * @param array $config
     */
    public function __construct($config = array())
    {
        if (count($config) == 0) {
            return;
        }
        foreach ($config as $key => $value) {  // set php.ini values
            ini_set($key, $value);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Initialize to Sess class configuration
     * 
     * @param array $sess session configuration array comes from sess config file
     * 
     * @return boolean
     */
    public function init($sess = array())
    {
        global $config, $logger;

        foreach (
            array(
            'cookie_name',
            'expiration',
            'expire_on_close',
            'encrypt_cookie',
            'request',
            'table_name',
            'match_ip',
            'match_useragent',
            'time_to_update'
            ) as $key) {
            $this->$key = $sess[$key];
        }

        $this->cookie_path   = (isset($sess['cookie_path'])) ? $sess['cookie_path'] : $config['cookie_path'];
        $this->cookie_domain = (isset($sess['cookie_domain'])) ? $sess['cookie_domain'] : $config['cookie_domain'];
        $this->cookie_prefix = (isset($sess['cookie_prefix'])) ? $sess['cookie_prefix'] : $config['cookie_prefix'];

        $request = $this->request;
        $this->request = $request();  // Set Request object

        // http://stackoverflow.com/questions/2615554/how-to-encrypt-session-id-in-cookie

        if ($this->expire_on_close) {  // Expire on close 
            session_set_cookie_params(0);
        } else {
            session_set_cookie_params($this->expiration, $this->cookie_path, $this->cookie_domain);
        }

        $this->now = $this->_getTime();
        $this->encryption_key = $config['encryption_key'];
        $this->time_reference = $config['time_reference'];

        session_name($this->cookie_prefix . $this->cookie_name);

        // http://stackoverflow.com/questions/6249707/check-if-php-session-has-already-started

        if (session_status() == PHP_SESSION_NONE) { // If another session_start() func is started before ?
            session_start();
        }

        if ($this->expiration == 0) { // Set the expiration two years from now.
            $this->expiration = (60 * 60 * 24 * 365 * 2);
        }

        $this->cookie_name = $this->cookie_prefix . $this->cookie_name; // Set the cookie name

        if ( ! $this->_read()) {
            $this->_create();
        } else {
            $this->_update();
        }

        $this->_flashdataSweep();  // delete old flashdata (from last request)
        $this->_flashdataMark();   // mark all new flashdata as old (data will be deleted before next request)

        $logger->debug('Session Native Driver Initialized');
        $logger->debug('Session routines successfully run');
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Fetch the current session data if it exists
     *
     * @access    public
     * @return    array() sessions.
     */
    public function _read()
    {
        global $logger;

        $session = (isset($_COOKIE[$this->cookie_name . '_userdata'])) ? $_COOKIE[$this->cookie_name . '_userdata'] : false; // Fetch the cookie

        if ($session === false) {  // No cookie?  Goodbye cruel world!...
            $logger->debug('A session cookie was not found');
            return false;
        }

        if ($this->encrypt_cookie == true) { // Decrypt the cookie data : ! "Encrypt Library Header redirect() Bug Fixed !"
            $key = $this->encryption_key;
            $session = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($session), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
        } else {
            $hash = substr($session, strlen($session) - 32);    // encryption was not used, so we need to check the md5 hash
            $session = substr($session, 0, strlen($session) - 32); // get last 32 chars

            if ($hash !== md5($session . $this->encryption_key)) {  // Does the md5 hash match?                                                        // This is to prevent manipulation of session data in userspace
                $logger->channel('security');
                $logger->alert('The session cookie data did not match what was expected. This could be a possible hacking attempt');
                $this->destroy();
                return false;
            }
        }
        $session = $this->_unserialize($session); // Unserialize the session array

        if ( ! is_array($session) OR ! isset($session['session_id'])          // Is the session data we unserialized an array with the correct format?
                OR ! isset($session['ip_address']) OR !isset($session['user_agent']) OR ! isset($session['last_activity'])) {
            $this->destroy();
            return false;
        }

        if (($session['last_activity'] + $this->expiration) < $this->now) {  // Is the session current?
            $this->destroy();
            return false;
        }

        if ($this->match_ip == true AND $session['ip_address'] != $this->request->getIpAddress()) { // Does the IP Match?
            $this->destroy();
            return false;
        }

        if ($this->match_useragent == true AND trim($session['user_agent']) != trim(substr($this->request->getServer('HTTP_USER_AGENT'), 0, 50))) {
            $this->destroy();       // Does the User Agent Match?
            return false;
        }

        if ($this->match_ip == true AND isset($_SESSION['ip_address']) AND $session['ip_address'] != $_SESSION['ip_address']) {
            $this->destroy();
            return false;
        }

        if ($this->match_useragent == true AND isset($_SESSION['user_agent']) AND $session['user_agent'] != $_SESSION['user_agent']) {
            $this->destroy();
            return false;
        }

        $this->userdata = $session;   // Session is valid!
        unset($session);
        return true;
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
        $this->userdata = array(
            'session_id' => session_id(),
            'ip_address' => $this->request->getIpAddress(),
            'user_agent' => substr($this->request->getServer('HTTP_USER_AGENT'), 0, 50),
            'last_activity' => $this->now
        );

        $_SESSION['session_id']    = $this->userdata['session_id'];
        $_SESSION['ip_address']    = $this->userdata['ip_address'];
        $_SESSION['user_agent']    = $this->userdata['user_agent'];
        $_SESSION['last_activity'] = $this->userdata['last_activity'];

        $this->_setCookie($this->userdata); // Write the cookie 
    }

    // --------------------------------------------------------------------

    /**
     * Update an existing session
     *
     * @access    public
     * @return    void
     */
    public function _update()
    {
        if (($this->userdata['last_activity'] + $this->time_to_update) >= $this->now) {  // We only update the session every five minutes by default
            return;
        }
        $this->userdata['last_activity'] = $this->now;

        // Update the session ID and last_activity
        // set cookie explicitly to only have our session data

        $cookie_data = array();
        foreach (array('session_id', 'ip_address', 'user_agent', 'last_activity') as $val) {
            $cookie_data[$val] = $this->userdata[$val];
        }
        $_SESSION['session_id']    = $this->userdata['session_id'];
        $_SESSION['last_activity'] = $this->userdata['last_activity'];

        $this->_setCookie($cookie_data); // Write the cookie
    }

    // --------------------------------------------------------------------

    /**
     * Regenerate the session id
     * 
     * @return void
     */
    public function regenerateId()
    {
        $old_session_id = session_id(); // copy old session data, including its id
        $old_session_data = $_SESSION;

        session_regenerate_id();     // regenerate session id and store it
        $new_sessid = session_id();

        session_id($old_session_id); // switch to the old session and destroy its storage
        session_destroy();

        session_id($new_sessid); // switch back to the new session id and send the cookie
        session_start();

        $_SESSION = $old_session_data; // restore the old session data into the new session

        $this->userdata = array(
            'session_id' => session_id(),
            'ip_address' => $this->request->getIpAddress(),
            'user_agent' => substr($this->request->getServer('HTTP_USER_AGENT'), 0, 50),
            'last_activity' => $this->now
        );

        $_SESSION['session_id']    = $this->userdata['session_id'];
        $_SESSION['ip_address']    = $this->userdata['ip_address'];
        $_SESSION['user_agent']    = $this->userdata['user_agent'];
        $_SESSION['last_activity'] = $this->userdata['last_activity'];

        $this->_setCookie($this->userdata); // Write the cookie 

        session_write_close(); // end the current session and store session data.
    }

    // --------------------------------------------------------------------

    /**
     * Write the session cookie
     *
     * @access    public
     * @return    void
     */
    public function _setCookie($cookie_data = null)
    {
        $cookie_data = $this->_serialize($cookie_data); // Serialize the userdata for the cookie

        if ($this->encrypt_cookie == true) { // Obullo Changes "Encrypt Library Header redirect() Bug Fixed !"
            $key = $this->encryption_key;
            $cookie_data = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cookie_data, MCRYPT_MODE_CBC, md5(md5($key))));
        } else {
            $cookie_data = $cookie_data . md5($cookie_data . $this->encryption_key); // if encryption is not used, 
            // we provide an md5 hash to prevent userside tampering
        }

        $expiration = ($this->expire_on_close) ? 0 : $this->expiration + time();

        // Set the cookie
        setcookie(
                $this->cookie_name . '_userdata', $cookie_data, $expiration, $this->cookie_path, $this->cookie_domain, 0
        );
    }

    // --------------------------------------------------------------------

    /**
     * Destroy the current session
     *
     * @access    public
     * @return    void
     */
    public function destroy()
    {
        if (isset($_COOKIE[$this->cookie_name])) {

            if (session_status() == PHP_SESSION_ACTIVE) { // http://stackoverflow.com/questions/13114185/how-can-you-check-if-a-php-session-exists
                session_destroy();
            }

            setcookie($this->cookie_name . '_userdata', '', ($this->now - 42000), $this->cookie_path, $this->cookie_domain
            );
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Check session id is expired
     * 
     * @return boolean 
     */
    public function isExpired()
    {
        if ( ! isset($_SESSION['last_activity'])) {
            return false;
        }
        $expire = $this->now - $this->expiration;
        if ($_SESSION['last_activity'] <= $expire) {
            return true;
        }
        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Fetch a specific item from the session array
     *
     * @access   public
     * @param    string
     * @return   string
     */
    public function get($item, $prefix = '')
    {
        return ( ! isset($_SESSION[$prefix . $item])) ? false : $_SESSION[$prefix . $item];
    }

    // --------------------------------------------------------------------

    /**
     * Fetch all session data
     *
     * @access    public
     * @return    mixed
     */
    public function getAllData()
    {
        return (isset($_SESSION)) ? $_SESSION : false;
    }

    // --------------------------------------------------------------------

    /**
     * Add or change data in the $_SESSION
     *
     * @access   public
     * @param    mixed
     * @param    string
     * @return   void
     */
    public function set($newdata = array(), $newval = '', $prefix = '')
    {
        if (is_string($newdata)) {
            $newdata = array($newdata => $newval);
        }
        if (sizeof($newdata) > 0) {
            foreach ($newdata as $key => $val) {
                $_SESSION[$prefix . $key] = $val;
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Delete a session variable from the $_SESSION
     *
     * @access    public
     * @return    void
     */
    public function remove($newdata = array(), $prefix = '')
    {
        if (is_string($newdata)) {
            $newdata = array($newdata => '');
        }
        if (sizeof($newdata) > 0) {
            foreach ($newdata as $key => $val) {
                unset($_SESSION[$prefix . $key]);
            }
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
    public function setFlash($newdata = array(), $newval = '')
    {
        if (is_string($newdata)) {
            $newdata = array($newdata => $newval);
        }
        if (sizeof($newdata) > 0) {
            foreach ($newdata as $key => $val) {
                $flashdata_key = $this->flashdata_key . ':new:' . $key;
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
    public function keepFlash($key)
    {
        // 'old' flashdata gets removed.  Here we mark all 
        // flashdata as 'new' to preserve it from _flashdataSweep()
        // Note the function will return false if the $key 
        // provided cannot be found
        $old_flashdata_key = $this->flashdata_key . ':old:' . $key;
        $value = $this->get($old_flashdata_key);

        $new_flashdata_key = $this->flashdata_key . ':new:' . $key;
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
    public function getFlash($key, $prefix = '', $suffix = '')
    {
        $flashdata_key = $this->flashdata_key . ':old:' . $key;
        $value = $this->get($flashdata_key);

        if ($value == '') {
            $prefix = '';
            $suffix = '';
        }
        return $prefix . $value . $suffix;
    }

    // ------------------------------------------------------------------------

    /**
     * Identifies flashdata as 'old' for removal
     * when _flashdataSweep() runs.
     *
     * @access    private
     * @return    void
     */
    public function _flashdataMark()
    {
        $userdata = $this->getAllData();
        foreach ($userdata as $name => $value) {
            $parts = explode(':new:', $name);
            if (is_array($parts) && count($parts) === 2) {
                $new_name = $this->flashdata_key . ':old:' . $parts[1];
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
    public function _flashdataSweep()
    {
        $userdata = $this->getAllData();
        foreach ($userdata as $key => $value) {
            if (strpos($key, ':old:')) {
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
    public function _getTime()
    {
        $time = time();
        if (strtolower($this->time_reference) == 'gmt') {
            $now = time();
            $time = mktime(gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now)
            );
        }
        return $time;
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
    public function _serialize($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (is_string($val)) {
                    $data[$key] = str_replace('\\', '{{slash}}', $val);
                }
            }
        } else {
            if (is_string($val)) {
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
     * @access   private
     * @param    array
     * @return   string
     */
    public function _unserialize($data)
    {
        $data = unserialize(stripslashes($data));

        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (is_string($val)) {
                    $data[$key] = str_replace('{{slash}}', '\\', $val);
                }
            }
            return $data;
        }
        return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
    }

}

/* End of file sess_native.php */
/* Location: ./packages/sess_native/releases/0.0.1/sess_native.php */