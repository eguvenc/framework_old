<?php

/*
|--------------------------------------------------------------------------
| Auth Package Configuration
|--------------------------------------------------------------------------
| Configure auth library options
|
*/
$auth = array(
    
    'db' => new Db,      // Set Database,
    'sess' => new Sess,  // Session Object
    'get' => new Get,    // Input Object
    'bcrypt' => new Bcrypt,  // Bcrypt password hash / verify object
    'session_prefix' => 'auth_',      // Set a prefix to prevent collisions with original session object.
    'username_col' => 'user_email',   // The name of the table field that contains the username.
    'password_col' => 'user_password', // The name of the table field that contains the password.
    'login_url'   => '/login',        // Redirect Url for Unsuccessfull logins
    'dashboard_url' => '/dashboard',  // Redirect Url Successfull logins

    // Security Settings
    'password_salt_str'   => '',        // Password salt string for more strong passwords. * Leave it blank if you don't want to use it.
    'algorithm'           => 'bcrypt',  // Whether to use "bcrypt" or "sha256" or "sha512" hash. ( Do not use md5 )
    'allow_login'         => true,      // Whether to allow logins to be performed on login form.
    'regenerate_sess_id'  => false,     // Set to true to regenerate the session id on every page load or leave as false to regenerate only upon new login.
);

// Auth Query
// Build your sql ( or nosql query ) using db or crud oject.

$auth['query'] = function($username) use($auth)
{
    $this->db->prep();
    $this->db->select('user_id, user_firstname, user_lastname, user_email');
    $this->db->where($auth['username_col'], ':username');
    $this->db->get('users');
    $this->db->bindParam(':username', $username, PARAM_STR, 60); // String (int Length),
    $this->db->exec();
    
    return $this->db->row();  // return to object.
};


/* End of file auth.php */
/* Location: .app/config/auth.php */