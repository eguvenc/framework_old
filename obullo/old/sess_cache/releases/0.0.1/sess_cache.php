<?php

/**
 * Session Cache Driver
 *
 * @package       packages
 * @subpackage    sess_cache
 * @category      sessions
 * @link
 */
Class Sess_Cache
{
    public $db;
    public $request;
    public $encrypt_cookie = false;
    public $expiration = '7200';
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
    public $userdata = array();

    // --------------------------------------------------------------------

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
            'db',
            'table_name',
            'match_ip',
            'match_useragent',
            'time_to_update'
        ) as $key) {
            $this->$key = $sess[$key];
        }

        $this->cookie_path = (isset($sess['cookie_path'])) ? $sess['cookie_path'] : $config['cookie_path'];
        $this->cookie_domain = (isset($sess['cookie_domain'])) ? $sess['cookie_domain'] : $config['cookie_domain'];
        $this->cookie_prefix = (isset($sess['cookie_prefix'])) ? $sess['cookie_prefix'] : $config['cookie_prefix'];

        $dbo = $this->db;    // set database;
        $this->db = $dbo();

        $this->now = $this->_getTime();
        $this->encryption_key = $config['encryption_key'];
        $this->time_reference = $config['time_reference'];

        if ($this->expiration == 0) { // Set the expiration two years from now.
            $this->expiration = (60 * 60 * 24 * 365 * 2);
        }

        $this->cookie_name = $this->cookie_prefix . $this->cookie_name; // Set the cookie name

        $request = $this->request;
        $this->request = $request();  // Set Request object

        if (!$this->_read()) {    // Run the Session routine. If a session doesn't exist we'll                          // create a new one.  If it does, we'll update it.
            $this->_create();
        } else {
            $this->_update();
        }

        $this->_flashdataSweep(); // Delete 'old' flashdata (from last request)
        $this->_flashdataMark();  // Mark all new flashdata as old (data will be deleted before next request)
        $this->_gC();             // Delete expired sessions if necessary

        $logger->debug('Session Database Driver Initialized');
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

        $session = (isset($_COOKIE[$this->cookie_name])) ? $_COOKIE[$this->cookie_name] : false;

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

            if ($hash !== md5($session . $this->encryption_key)) {  // Does the md5 hash match?  // This is to prevent manipulation of session data in userspace
                $logger->channel('security');
                $logger->alert('The session cookie data did not match what was expected. This could be a possible hacking attempt');
                $this->destroy();
                return false;
            }
        }

        $session = $this->_unserialize($session); // Unserialize the session array

        if ( ! is_array($session) 
            OR ! isset($session['session_id'])          // Is the session data we unserialized an array with the correct format?
            OR ! isset($session['ip_address']) 
            OR ! isset($session['user_agent']) 
            OR ! isset($session['last_activity'])
        ) {
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

        $query = $this->db->get($session['session_id']);
        $query = $this->_unserialize($query);

        if ($this->match_ip == true AND $session['ip_address'] != $query['ip_address']) {
            $this->destroy();

            return false;
        }

        if ($this->match_useragent == true AND $session['user_agent'] != $query['user_agent']) {
            $this->destroy();
            return false;
        }

        // Is there custom data?  If so, add it to the main session array
        if (empty($query) OR $query == '') {   // No result?  Kill it! // Obullo changes ..
            $this->destroy();
            return false;
        }

        if (isset($query['user_data']) AND $query['user_data'] != '') {
            $custom_data = $this->_unserialize($query['user_data']);
            if (is_array($custom_data)) {
                foreach ($custom_data as $key => $val) {
                    $session[$key] = $val;
                }
            }
        }
        $this->userdata = $session;   // Session is valid!
        unset($session);
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Write the session data
     *
     * @access    public
     * @return    void
     */
    public function _write()
    {
        $custom_userdata = $this->userdata;  // set the custom userdata, the session data we will set in a second
        $cookie_userdata = array();

        // Before continuing, we need to determine if there is any custom data to deal with.
        // Let's determine this by removing the default indexes to see if there's anything left in the array
        // and set the session data while we're at it

        foreach (array('session_id', 'ip_address', 'user_agent', 'last_activity') as $val) {
            unset($custom_userdata[$val]);
            $cookie_userdata[$val] = $this->userdata[$val];
        }

        // Did we find any custom data?  If not, we turn the empty array into a string
        // since there's no reason to serialize and store an empty array in the DB

        if (count($custom_userdata) === 0) {
            $custom_userdata = '';
        } else {
            $custom_userdata = $this->_serialize($custom_userdata); // Serialize the custom data array so we can store it
        }

        $this->_replace($this->userdata['session_id'], $this->userdata['session_id'], $this->userdata, time() + $this->expiration);


        // Write the cookie.  Notice that we manually pass the cookie data array to the
        // _setCookie() function. Normally that function will store $this->userdata, but 
        // in this case that array contains custom data, which we do not want in the cookie.

        $this->_setCookie($cookie_userdata);
    }

    // --------------------------------------------------------------------

    /**
     * Create a new session
     *
     * @access    public
     * @return    void
     */
    public function _create()
    {
        $sessid = '';
        while (strlen($sessid) < 32) {
            $sessid .= mt_rand(0, mt_getrandmax());
        }

        $sessid .= $this->request->getIpAddress(); // To make the session ID even more secure we'll combine it with the user's IP

        $this->userdata = array(
            'session_id' => md5(uniqid($sessid, true)),
            'ip_address' => $this->request->getIpAddress(),
            'user_agent' => substr($this->request->getServer('HTTP_USER_AGENT'), 0, 50),
            'last_activity' => $this->now
        );

        $this->db->set($this->userdata['session_id'], $this->_serialize($this->userdata), time() + $this->expiration);
        $this->_setCookie(); // Write the cookie 
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
        if (($this->userdata['last_activity'] + $this->time_to_update) >= $this->now) { // We only update the session every five minutes by default
            return;
        }
        $this->userdata['last_activity'] = $this->now;

        $cookie_data = null;    // _setCookie() will handle this for us if we aren't using database sessions
        // by pushing all userdata to the cookie
        // Update the session ID and last_activity field in the DB if needed
        // set cookie explicitly to only have our session data

        $cookie_data = array();
        foreach (array('session_id', 'ip_address', 'user_agent', 'last_activity') as $val) {
            $cookie_data[$val] = $this->userdata[$val];
        }
        $this->_replace($this->userdata['session_id'], null, $this->userdata, time() + $this->expiration);
        $this->_setCookie($cookie_data); // Write the cookie
    }

    // ------------------------------------------------------------------------

    /**
     * Regenerate the session id
     * 
     * @return void
     */
    public function regenerateId()
    {
        $old_sessid = $this->userdata['session_id']; // Save the old session id so we know which record to  
        $new_sessid = '';                            // update in the database if we need it
        while (strlen($new_sessid) < 32) {
            $new_sessid .= mt_rand(0, mt_getrandmax());
        }
        $new_sessid .= $this->request->getIpAddress();         // To make the session ID even more secure
        $new_sessid = md5(uniqid($new_sessid, true));   // Turn it into a hash

        $this->userdata['session_id'] = $new_sessid; // Update the session data in the session data array
        $this->userdata['last_activity'] = $this->now;

        $this->_replace($old_sessid, $new_sessid, $this->userdata, time() + $this->expiration);

        $cookie_data = array();
        foreach (array('session_id', 'ip_address', 'user_agent', 'last_activity') as $val) {
            $cookie_data[$val] = $this->userdata[$val];
        }
        $this->_setCookie($cookie_data); // Write the cookie
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
            if (is_array($parts) AND count($parts) === 2) {
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
    private function _flashdataSweep()
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
     * Write the session cookie
     *
     * @access    private
     * @return    void
     */
    private function _setCookie($cookie_data = null)
    {
        if (is_null($cookie_data)) {
            $cookie_data = $this->userdata;
        }
        $cookie_data = $this->_serialize($cookie_data); // Serialize the userdata for the cookie

        if ($this->encrypt_cookie == true) { // "Encrypt Library Header redirect() Bug Fixed !"
            $key = $this->encryption_key;
            $cookie_data = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cookie_data, MCRYPT_MODE_CBC, md5(md5($key))));
        } else {
            $cookie_data = $cookie_data . md5($cookie_data . $this->encryption_key); // if encryption is not used, 
            // we provide an md5 hash to prevent userside tampering
        }

        $expiration = ($this->expire_on_close) ? 0 : $this->expiration + time();

        // Set the cookie
        setcookie(
                $this->cookie_name, $cookie_data, $expiration, $this->cookie_path, $this->cookie_domain, 0
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
    private function _serialize($data)
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
    private function _unserialize($data)
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
    function _gC()
    {
        // no need we use expiration
    }

    // ------------------------------------------------------------------------

    /**
     * Destroy the current session
     *
     * @access    public
     * @return    void
     */
    public function destroy()
    {
        // Db driver changes..
        // -------------------------------------------------------------------

        if (isset($this->userdata['session_id'])) { // Kill the session DB row
            $this->db->where('session_id', $this->userdata['session_id']);
            $this->db->delete($this->table_name);
        }
        // -------------------------------------------------------------------
        // Kill the cookie
        setcookie(
                $this->cookie_name, addslashes(serialize(array())), ($this->now - 31500000), $this->cookie_path, $this->cookie_domain, false
        );
    }

    // ------------------------------------------------------------------------

    /**
     * Fetch a specific item from the session array
     *
     * @access   public
     * @param    string
     * @return   string
     */
    public function get($item, $prefix = '')
    {
        return ( ! isset($this->userdata[$prefix . $item])) ? false : $this->userdata[$prefix . $item];
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
        return (!isset($this->userdata)) ? false : $this->userdata;
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
    public function setFlash($newdata = array(), $newval = '')  // ( obullo changes ... )
    {
        if (is_string($newdata)) {
            $newdata = array($newdata => $newval);
        }
        if (count($newdata) > 0) {
            foreach ($newdata as $key => $val) {
                $flashdata_key = $this->flashdata_key . ':new:' . $key;
                $this->set($flashdata_key, $val);
            }
        }
    }

    // --------------------------------------------------------------------

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
    public function getFlash($key, $prefix = '', $suffix = '')  // obullo changes ...
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
     * Keeps existing flashdata available to next request.
     *
     * @access   public
     * @param    string
     * @return   void
     */
    public function keepFlash($key) // ( obullo changes ...)
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

    // --------------------------------------------------------------------

    /**
     * Add or change data in the "userdata" array
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
        if (count($newdata) > 0) {
            foreach ($newdata as $key => $val) {
                $this->userdata[$prefix . $key] = $val;
            }
        }
        $this->_write();
    }

    // ------------------------------------------------------------------------

    /**
     * Delete a session variable from the "userdata" array
     *
     * @access    public
     * @return    void
     */
    public function remove($newdata = array(), $prefix = '')
    {
        if (is_string($newdata)) {
            $newdata = array($newdata => '');
        }
        if (count($newdata) > 0) {
            foreach ($newdata as $key) {
                unset($this->userdata[$prefix . $key]);
            }
        }
        $this->_write();
    }

    // ------------------------------------------------------------------------

    /**
     * Check session id is expired
     * 
     * @return boolean 
     */
    public function isExpired()
    {
        if ( ! isset($this->userdata['last_activity'])) {
            return false;
        }
        $expire = $this->now - $this->expiration;
        if ($this->userdata['last_activity'] <= $expire) {
            return true;
        }
        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Read & unserialize data update it
     * then return to serialized string
     * 
     * @param string  $key        cache existing key
     * @param string  $newkey     cache new key
     * @param array   $data       new update key and values
     * @param integer $expiration ttl
     * 
     * @return string       serialized data
     */
    private function _replace($key, $newkey = null, $data = array(), $expiration = 0)
    {
        $cached_userdata = $this->db->get($key);

        $replace_data = $this->_unserialize($cached_userdata);
        foreach ($replace_data as $value) {
            if (isset($data[$value])) {
                $replace_data[$value] = $data[$value];
            }
        }
        $this->db->delete($key);  // delete old data

        if (empty($newkey)) {  // use old key if new key not exists.
            $newkey = $key;
        }
        $new_data = $this->_serialize($replace_data);
        $this->db->set($newkey, $new_data, $expiration); // set new data
    }

}

/* End of file sess_cache.php */
/* Location: ./packages/sess_cache/releases/0.0.1/sess_cache.php */