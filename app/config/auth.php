<?php
defined('BASE') or exit('Access Denied!');
/*
|--------------------------------------------------------------------------
| Auth Library Options
|--------------------------------------------------------------------------
|
| Configure auth library settings
|
*/
$auth['session_prefix']      = 'auth_';     // Set a auth session prefix to prevent collisions.
$auth['db_var']              = 'db';        // Database connection variable
$auth['tablename']           = 'users';     // The name of the database tablename
$auth['username_col']        = 'user_email';   // The name of the table field that contains the username.
$auth['password_col']        = 'user_password';  // The name of the table field that contains the password.
$auth['username_length']     = '60';        // The string length of the username.
$auth['password_length']     = '60';        // The string length of the password.
$auth['md5']                 = TRUE;        // Whether to use md5 hash ?
$auth['allow_login']         = TRUE;        // Whether to allow logins to be performed on this page.

$auth['post_username']       = 'user_email';     // The name of the form field that contains the username to authenticate.
$auth['post_password']       = 'user_password';  // The name of the form field that contains the password to authenticate.
$auth['advanced_security']   = TRUE;        // Whether to enable the advanced security features. 
$auth['query_binding']       = TRUE;        // Whether to enable the PDO query binding feature for security.
$auth['regenerate_sess_id']  = FALSE;       // Set to TRUE to regenerate the session id on every page load or leave as FALSE to regenerate only upon new login.

$auth['not_ok_url']          = '/login';     // Redirect Url for Unsuccessfull logins
$auth['ok_url']              = '/dashboard'; // Redirect Url Successfull logins
                                             
$auth['fields']              = array(       // Session Container db table fields.
                                'user_id', 
                                'user_firstname', 
                                'user_lastname', 
                                'user_email',
                                'user_active', 
                                'user_gender',
                            );

/* End of file auth.php */
/* Location: .app/config/auth.php */