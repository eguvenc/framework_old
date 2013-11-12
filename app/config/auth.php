<?php

/*
|--------------------------------------------------------------------------
| Auth Package Configuration
|--------------------------------------------------------------------------
|
| Configure auth library options
|
*/

$auth['table']               = 'users';
$auth['session_prefix']      = 'auth_';     // Set a auth session prefix to prevent collisions.
$auth['db_var']              = 'db';        // Database connection variable
$auth['username_col']        = 'user_email';   // The name of the table field that contains the username.
$auth['password_col']        = 'user_password';  // The name of the table field that contains the password.
$auth['post_username']       = 'user_email';     // The name of the form field that contains the username to authenticate.
$auth['post_password']       = 'user_password';  // The name of the form field that contains the password to authenticate.
$auth['login_url']           = '/login';     // Redirect Url for Unsuccessfull logins
$auth['dashboard_url']       = '/dashboard'; // Redirect Url Successfull logins
$auth['fields']              = array(        // Default db table select fields.
                                'user_id', 
                                'user_firstname', 
                                'user_lastname', 
                                'user_email',
                                'user_active'
                            );

/*
|--------------------------------------------------------------------------
| Security Settings
|--------------------------------------------------------------------------
|
| Default algoritm is blowfish no need to use password for bcrypt algorithm.
|
*/
$auth['password_salt_str']   = '';          // Password salt string for more strong passwords. * Leave it blank if you don't want to use it.
$auth['algorithm']   		 = 'bcrypt';    // Whether to use "bcrypt" or "sha256" or "sha512" hash. ( Do not use md5 )
$auth['allow_login']         = true;        // Whether to allow logins to be performed on this page.
$auth['xss_clean']           = true;        // Whether to enable the xss clean.
$auth['regenerate_sess_id']  = false;       // Set to true to regenerate the session id on every page load or leave as false to regenerate only upon new login.

/*
|--------------------------------------------------------------------------
| Customize your auth query
|--------------------------------------------------------------------------
| 
| Build your sql or nosql query using crud oject.
|
*/
$auth['query'] = array(function($username) use($auth)
{
    $this->db->prep();
    $this->db->select(implode(',', $auth['fields']));
    $this->db->where($auth['username_col'], ':username');
    $this->db->get($auth['table']);
    $this->db->bindParam(':username', $username, PARAM_STR, 60); // String (int Length),
    $this->db->exec();

    return $this->db->row();  // return to object.
});


/* End of file auth.php */
/* Location: .app/config/auth.php */