<?php

/**
 * Auth Class
 *
 * A lightweight and simple user authentication control class.
 *
 * @package       packages
 * @subpackage    auth
 * @category      authentication
 * @link
 */

Class Auth {
   
    public $session_prefix     = 'auth_';
    public $db_var             = 'db';        // database connection variable
    public $tablename          = '';          // The name of the database tablename
    public $username_col       = 'username';  // The name of the table field that contains the username.
    public $password_col       = 'password';  // The name of the table field that contains the password.
    public $md5                = true;        // Whether to use md5 hash.
    public $allow_login        = true;        // Whether to allow logins to be performed on this page.

    public $post_username      = '';     // The name of the form field that contains the username to authenticate.
    public $post_password      = '';     // The name of the form field that contains the password to authenticate.
    public $password_salt      = true;   // Whether to use password salt.
    public $password_salt_str  = '';     // Password salt string.
    public $advanced_security  = true;   // Whether to enable the advanced security features.
    public $query_binding      = true;   // Whether to enable the PDO query binding feature for security.
    public $regenerate_sess_id = false;  // Set to true to regenerate the session id on every page load or leave as false to regenerate only upon new login.
    
    public $fail_url           = '/login';
    public $ok_url             = '/dashboard';
    public $fields             = array();
    public $row                = false;    // SQL Query result as row
    
    /**
    * Constructor
    *
    * Sets the variables and runs the compilation routine
    *
    * @version   0.1
    * @access    public
    * @return    void
    */
    public function __construct($no_instance = true, $config = array())
    {   
        if($no_instance)
        {
            getInstance()->auth = $this; // Make available it in the controller $this->auth->method();
        }
   
        if (count($config) > 0)
        {
            $this->init($config);
        }
        
        log\me('debug', "Auth Class Initialized");
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Initalize and grab instance of the auth.
     * 
     * @param array $params
     * @return object
     */
    public function init($params = array())
    {
        $auth   = getConfig('auth');
        $config = array_merge($auth , $params);

        foreach($config as $key => $val)
        {
            $this->{$key} = $val;
        }
        
        new sess\start();

        $database = new Db(false);
        $this->db = $database->connect($this->db_var);
        
        return ($this);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Set database select names
    * 
    * @param string $select database select fields
    */
    public function select($select = '')
    {
        if($select == '')
        {
            $this->select_data = $this->item('fields');
            
            return;
        }
        
        if(is_array($select))
        {
            $this->select_data = $select;
        } 
        else
        {
            $select = trim($select, ',');
            $this->select_data = explode(',', $select);
            $this->select_data = array_map('trim', $this->select_data);
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Send post query to login
    * 
    * @param string $username  manually login username
    * @param string $password  manually login password
    * @return bool | object
    */
    public function query($username = '', $password = '')
    {
        if($this->item('allow_login') == false)
        {
            return false;
        }
        
        if( empty($username) AND empty($password) )
        {
            $username = i\getPost($this->post_username, $this->advanced_security);
            $password = i\getPost($this->post_password);            
        } 
        
        if($this->advanced_security)
        {
            $this->password_salt = true;
            $this->query_binding = true;
        }
        
        if($this->password_salt AND $this->password_salt_str == '')
        {
            throw new Exception('Security alert: Please set a password salt string from app/config/auth'.EXT);
        }
        
        $username = trim($username);
        $password = ($this->password_salt) ? ($this->password_salt_str.trim($password)) : trim($password);

        if($this->md5 AND ! $this->_isMd5($password))
        {
            $password = md5($password);
        }

        if(db('driver') == 'mongo')
        {
            $this->db->select($this->select_data);
            $this->db->where($this->username_col, $username);
            $this->db->where($this->password_col, $password);
            
            $docs = $this->db->get($this->tablename);
            
            if( ! $docs->hasNext())
            {
                return false;
            }
            
            $this->row = (object) $docs->getNext();

            if($this->row != null AND isset($this->row->{$this->username_col}))
            {
                return $this->row;
            }
        }
        else 
        {
            if($this->query_binding)         // Use bind ( Secure Pdo Query ).
            {
                $this->db->prep();      
                $this->db->select(implode(',', $this->select_data));
                $this->db->where($this->username_col, ':username');
                $this->db->where($this->password_col, ':password');
                $this->db->get($this->tablename);

                $this->db->bindParam(':username', $username, PARAM_STR, $this->username_length); // String (int Length)
                $this->db->bindParam(':password', $password, PARAM_STR, $this->password_length); // String (int Length)

                $query = $this->db->exec();
                $this->row = $query->row();
            } 
            else 
            {
                $this->db->select(implode(',', $this->select_data));
                $this->db->where($this->username_col, $username);
                $this->db->where($this->password_col, $password);

                $query = $this->db->get($this->tablename);
                $this->row = $query->row();
            }
            
            if(is_object($this->row) AND isset($this->row->{$this->username_col}))
            {   
                return $this->row;
            }
        }
      
        return false;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Autheticate the user if login is successfull !
     * 
     * @return bool
     */
    public function setAccess($fake_auth = false)
    {
        if($fake_auth)
        {
            sess\set('ok', 1, $this->session_prefix);  // Just authenticate the user.
            return;
        }
        
        $row = $this->getRow();
        
        if(is_object($row) AND isset($row->{$this->username_col}))
        {            
            $this->setAuth($this->select_data);  // auth is ok ?
            
            return true;
        }
        
        return false;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get validated user sql query result object
     *
     * @return type 
     */
    public function getRow()
    {
        return $this->row;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get User is authenticated 
     * if its ok it returns to true otherwise false
     * 
     * @return boolean 
     */
    public function access()
    {
        if(sess\get('ok', $this->session_prefix) == 1)  // auth is ok ?
        {
            return true;
        }
        
        return false;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Check Auth is Fail and Redirect to provided url.
     * 
     * @param type $callback
     * @param type $params
     * @return boolean | void ( callback function )
     */
    public function accessRedirect($redirect = '', $urlencode = true)
    {
        if( ! $this->check())  // auth is NOT ok ?
        {  
            $redirect_url = ($redirect == '') ? baseUrl($this->fail_url) : baseUrl($redirect);
            $redirect_url = $redirect_url.'?redirect='.getInstance()->uri->requestUri($urlencode);

            redirect($redirect_url); 
        }
        
        return;
    }

    // ------------------------------------------------------------------------
    
    /**
    * Retrieve authenticated user session data
    * 
    * @param string $key
    * @return mixed
    */
    public function data($key = '')
    {
        if($key == '')
        {
            return sess\allData();
        }
        
        return sess\get($key, $this->session_prefix);
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Set session auth data to user session container
    * 
    * @param string $key
    * @return void
    */
    public function setData($key, $val = '')
    {
        if(is_array($key))
        {
            sess\set($key, '', $this->session_prefix);
            return;
        }
        
        sess\set($key, $val, $this->session_prefix);
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Unset session auth data from user session container
    * 
    * @param string $key
    * @return void
    */
    public function unsetData($key)
    {
        if(is_array($key))
        {
            sess\remove($key, $this->session_prefix);
            return;
        }
        
        sess\remove($key, $this->session_prefix);
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
    public function item($key)
    {
        return $this->{$key};
    }
    
    //-------------------------------------------------------------------------
    
    public function setExpire() {}
    public function setIdle() {}
    
    // ------------------------------------------------------------------------
    
    /**
     * Check password is md5.
     * 
     * @access private
     * @param string $md5
     * @return boolean 
     */
    public function _isMd5($md5)
    {
        if(empty($md5)) 
        {
            return false;
        }
        
        return preg_match('/^[a-f0-9]{32}$/', $md5);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Store auth data to session container.
     * 
     * @param array $data 
     * @return void
     */
    public function setAuth($data = array())
    {
        $row = $this->getRow();

        sess\set('ok', 1, $this->session_prefix);  // Authenticate the user.
        
        $sess_data = array();
        foreach($data as $key)
        {
            $sess_data[$key] = $row->{$key};
        }
        
        sess\set($sess_data, '', $this->session_prefix);   // Store user data to session container.
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Logout user, destroy the sessions.
    * 
    * @param bool $destroy - whether to use session destroy function
    * @return void 
    */
    public function logout($destroy = true)
    {
        sess\remove('ok', $this->session_prefix);
        
        if($destroy)
        {
            sess\destroy();
            return;
        }
        
        $user_data = sess\allData();
        sess\remove($user_data, $this->session_prefix);
    }
    
}

// END Auth Class

/* End of file auth.php */
/* Location: ./packages/auth/releases/0.0.1/auth.php */