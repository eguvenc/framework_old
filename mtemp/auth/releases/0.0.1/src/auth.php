<?php

/*
|--------------------------------------------------------------------------
| Auth Package Configuration
|--------------------------------------------------------------------------
|
| Configure auth library options
|
*/
$auth['session_prefix']      = 'auth_';     // Set a auth session prefix to prevent collisions.
$auth['db_var']              = 'db';        // Database connection variable
$auth['tablename']           = 'users';     // The name of the database tablename
$auth['username_col']        = 'user_email';   // The name of the table field that contains the username.
$auth['password_col']        = 'user_password';  // The name of the table field that contains the password.
$auth['username_length']     = '60';        // The string length of the username.
$auth['password_length']     = '60';        // The string length of the password.
$auth['password_salt']       = true;        // Whether to use password salt ?
$auth['password_salt_str']   = '';          // Password salt string for more strong passwords.You need to use same string in the user registration.
$auth['md5']                 = true;        // Whether to use md5 hash ?
$auth['allow_login']         = true;        // Whether to allow logins to be performed on this page.

$auth['post_username']       = 'user_email';     // The name of the form field that contains the username to authenticate.
$auth['post_password']       = 'user_password';  // The name of the form field that contains the password to authenticate.
$auth['advanced_security']   = true;        // Whether to enable the advanced security features. 
$auth['query_binding']       = true;        // Whether to enable the PDO query binding feature for security.
$auth['regenerate_sess_id']  = false;       // Set to true to regenerate the session id on every page load or leave as false to regenerate only upon new login.

$auth['fail_url']            = '/login';     // Redirect Url for Unsuccessfull logins
$auth['ok_url']              = '/dashboard'; // Redirect Url Successfull logins
                                             
$auth['fields']              = array(       // Session Container db table fields.
                                'user_id', 
                                'user_firstname', 
                                'user_lastname', 
                                'user_email',
                                'user_active', 
                                'user_gender',
                            );
