<?php

/**
 * Simple Auth Class
 *
 * A lightweight and simple user authentication control class.
 *
 * @package       packages
 * @subpackage    auth
 * @category      authentication
 * 
 * @link(Safely Store a Password, http://codahale.com/how-to-safely-store-a-password/)
 * @link(Zend Crypt Password, http://framework.zend.com/manual/2.0/en/modules/zend.crypt.password.html)
 * @link(Bcyrpt Class, https://github.com/cosenary/Bcrypt-PHP-Class)
 */
Class Auth
{
    public $database;       // Databasse object variable name
    public $sess;           // Session Object
    public $algorithm;      // Password hash / verify object
    public $session_prefix = 'auth_';
    public $allow_login    = true;    // Whether to allow logins to be performed on this page.
    public $regenerate_sess_id = false;   // Set to true to regenerate the session id on every page load or leave as false to regenerate only upon new login.

    /**
     * Constructor
     *
     * Sets the variables and runs the compilation routine
     *
     * @access    public
     * @return    void
     */

    public function __construct()
    {
        global $logger;

        $auth = getConfig('auth');
        foreach ($auth as $key => $val) {
            $this->{$key} = $val;
        }
        
        $session    = $auth['session'];    // call new Sess object
        $class      = $session();
        $this->sess = $class::$driver;

        if ($this->regenerate_sess_id) {   // regenerate the session id on every page load
            $this->sess->regenerateId();
        }

        if ( ! isset(getInstance()->auth)) {
            getInstance()->auth = $this;  // Make available it in the controller.
        }
        $logger->debug('Auth Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Create Password Hash
     * 
     * @param string $password user password
     * 
     * @return string $hash
     */
    public function hashPassword($password)
    {
        $hashPassword = $this->extend['hashPassword']; //  run the closure function
        return $hashPassword($password);
    }

    // ------------------------------------------------------------------------

    /**
     * Create Password Hash
     * 
     * @param string $password   user password
     * @param string $dbPassword database password
     * 
     * @return string $hash
     */
    public function verifyPassword($password, $dbPassword)
    {
        $algorithm_closure = $this->algorithm;
        $algorithm = $algorithm_closure();

        if (is_object($algorithm)) {
            $verifyPassword = $this->extend['verifyPassword'];      //  run the closure function
            return $verifyPassword($password, $dbPassword);
        } else {  // if it is a native hash() function
            return $algorithm;
        }
        return false;
    }

    // ------------------------------------------------------------------------   

    /**
     * Authorize to User
     * 
     * @return void
     */
    public function authorize()
    {
        $this->sess->set('hasIdentity', 'yes', $this->session_prefix);  // Authenticate the user.
    }

    // ------------------------------------------------------------------------

    /**
     * Autheticate and set Identity items if login is successfull !
     * 
     * @param array $identities
     * @param bool $fakeAuth authorize to user.
     * @return bool
     */
    public function setIdentity($key, $val = '')
    {
        if (is_array($key)) {
            $this->sess->set($key, '', $this->session_prefix);
            return;
        }
        $this->sess->set($key, $val, $this->session_prefix);
    }

    // ------------------------------------------------------------------------

    /**
     * Get User has auth access 
     * if its ok it returns to true otherwise false
     * 
     * @return boolean 
     */
    public function hasIdentity()
    {
        if ($this->sess->get('hasIdentity', $this->session_prefix) == 'yes') {  // auth is ok ?
            return true;
        }
        return false;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Retrieve authenticated user session data
     * 
     * @param string $key
     * @return mixed
     */
    public function getIdentity($key = '')
    {
        if ($key == '') {
            return;
        }
        return $this->sess->get($key, $this->session_prefix);
    }

    // ------------------------------------------------------------------------

    /**
     * Unset session auth data from user session container
     * 
     * @param string $key
     * @return void
     */
    public function removeIdentity($key)
    {
        if (is_array($key)) {
            $this->sess->remove($key, $this->session_prefix);
            return;
        }
        $this->sess->remove($key, $this->session_prefix);
    }

    // ------------------------------------------------------------------------

    /**
     * Override to auth configuration.
     * 
     * @param string $key
     * @param mixed $val 
     */
    public function setItem($key, $val)
    {
        $this->{$key} = $val;
    }

    //-------------------------------------------------------------------------

    /**
     * Get auth config item.
     * 
     * @param string $key
     * @return mixed
     */
    public function getItem($key)
    {
        return $this->{$key};
    }

    //-------------------------------------------------------------------------
    // @todo
    public function setExpire(){}
    public function setIdle(){}

    // ------------------------------------------------------------------------

    /**
     * Remove the identity of user
     * 
     * @return void 
     */
    public function clearIdentity()
    {
        $this->sess->remove($this->getAllData());
    }

    // ------------------------------------------------------------------------

    /**
     * Get all identity data
     * 
     * @return type
     */
    public function getAllData()
    {
        $identityData = array();
        foreach ($this->sess->getAllData() as $key => $val) {
            if (strpos($key, $this->session_prefix) === 0) { // if key == auth_
                $identityData[substr($key, 5)] = $val;
            }
        }
        return $identityData;
    }

}

// END Auth Class

/* End of file auth.php */
/* Location: ./packages/auth/releases/0.0.1/auth.php */