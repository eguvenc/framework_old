## Auth Class

------

Auth Class provides a lightweight and simple auth implementation for user authentication management.

### Initializing the Class

------

```php
new Auth();
$this->auth->method();
```

Once loaded, the Auth object will be available using: $this->auth->method();

### Grabbing the Instance

------

Also using new Auth(false); boolean you can grab the instance of Obullo libraries,"$this->auth->method()" will not available in the controller.

```php
$auth = new Auth(false);
$auth->method();
```

### Configuring Auth Options

------

You can set authentication options using <dfn>/app/config/auth.php</dfn> file.

```php
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


// End of file auth.php
// Location: .app/config/auth.php
```

### Sending Data to Auth Query

------

```php
$auth = new Auth(false);
$auth->select('user_username, user_password, user_active');
$auth->query();
$row = $auth->getRow();
        
if($row ! == false)
{
    if($row->user_active == 0)
    {
       echo 'Please activate your membership.';
    } 
    else 
    {
       $auth->setAccess();    //  Everythings ok ! Authenticate the user.
    }
}
```

### Checking Auth

------

```php
new Auth();

if($this->auth->access())
{
     echo 'Great you are authorized to view this page !'; 
}
```


### Checking Auth Using Quick Access

------

```php
$auth = new Auth(false);

if($auth->access())
{
     echo 'Great you are authorized to view this page !'; 
}
```

### Checking Auth and Redirect ( Check access and redirect users to login Page )

Redirect user to <b>/login</b> page for unauthenticated page views. Redirect page is a configurable option in auth.php config file.

```php
$this->auth->accessRedirect(); http://example.com/settings?a=1&b=2 Unauthenticated user request
```

For example if an <b>unauthenticated</b> user want to display the <b>/settings</b> page, <b>$this->auth->accessRedirect()</b> will redirect user to the login page.

```php
http://example.com/login?redirect=%2Fsettings%3Fa%3D1%26b%3D2
```

Don't forget we use $this->auth->accessRedirect() designed for <b>unauthenticated</b> page views.

And you may want to create hidden redirect input element to your login screen.

```php
<?php echo form\open('login/post', array('method' => 'POST')); ?>
<?php echo form\hidden('redirect', i\get('redirect')); ?>
```

So now you able to redirect user to the redirect url. You need to check redirect input using i\get('redirect') in your login page.

### Checking Auth and Manually Redirect

------

```php
$this->auth->accessRedirect('/login',  $urlencode = false);
```

### Manually Checking Auth and Redirect

------

Redirect user to <b>/dashboard</b> page to <b>authenticated</b> page views. Dashboard page is a configurable option in your <dfn>app/config/auth.php</dfn> file.

```php
if($this->auth->access())
{
    redirect($this->auth->item('ok_url'));
}
It's redirect request to /dashboard url as default.
```

### Getting User Data From Sessions

------

```php
$this->auth->data('username');
```

### Getting All User Data

------

```php
print_r($this->auth->data());
```

### Logout User

------

This function will destroy the user auth info and selected user data.

```php
$this->auth->logout();
```

### Function Reference

------

#### $this->auth->select('field1, field3, field3 ..')

Creates database select string according to your table fields. This function store all selected fields to session container.

#### $this->auth->attempt($username = '', $password = '')

Tries authentication attempt and do sql query using username and password combination, if you not provide any username data it will look at $_REQUEST data.

#### $this->auth->getRow()

Get validated user sql query result in object.

#### $this->auth->setAccess($fake_auth = false)

Authenticate the selected user and store user data to session container. If you provide $fake_auth = true, you can set authentication to user without database selection. For example we use this feature for who want to login with facebook connect button.

#### $this->auth->accessRedirect($url = ' ', $urlencode = false)

Check auth is fail and redirect it to provided url.

#### $this->auth->access()

Check user is authenticated if its ok it returns to true otherwise false.

#### $this->auth->data('key')

Retrieve authenticated user session data.

#### $this->auth->setData($key, $val)

Set extra data to authenticated user container.

#### $this->auth->unsetData($key)

Unset data from authenticated user container.

#### $this->auth->item($key)

Get auth config item.

#### $this->auth->setItem($key, $val)

Set auth config item.

#### $this->auth->setAuth($data = array())

Store an auth data to session container.

#### $this->auth->logout($destroy = true)

Logout user, destroy all session data. By default all sessions will be destroyed if you use <var>$destroy = false</var> option, only session auth data will be removed from sessions.