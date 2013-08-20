## Auth Class

------

Auth Class provides a lightweight and simple auth implementation for user authentication management.

### Initializing the Class

------

```php
new Auth();
```

Once loaded, the Auth object will be available using: <dfn>$this->auth->method();</dfn>

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
$auth['regenerate_sess_id']  = FALSE;       // Set to TRUE to regenerate the session id on every page load or leave .....

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

// End of file auth.php
// Location: .app/config/auth.php
```

### Sending Data to Auth Query

------

```php
$auth = new Auth(false);
$auth->select('user_username, user_password, user_active');
$row = $auth->get();
        
if($row ! == FALSE)
{
    if($row->user_active == 0)
    {
       echo 'Please activate your membership.';
    } 
    else 
    {
       $auth->set();    //  Everythings ok ! Authenticate the user !! 
    }
}
```

### Checking Auth

------

```php
new Auth();

if($this->auth->check())
{
     echo 'Great you are authorized to view this page !'; 
}
```


### Checking Auth Using Quick Access

------

```php
$auth = new Auth(false);

if($auth->check())
{
     echo 'Great you are authorized to view this page !'; 
}
```

### Checking Auth is Fail and Redirect ( Non-Auth Redirects )

Redirect user to <b>/login</b> page for unauthenticated page views. Redirect page is a configurable option in auth.php config file.

```php
$this->auth->redirect(); http://example.com/settings?a=1&b=2 Unauthenticated user request
```

For example if an <b>unauthenticated</b> user want to display your <b>/settings</b> page <b>$this->auth->redirect()</b> function will redirect to user this url.

```php
http://example.com/login?redirect=%2Fsettings%3Fa%3D1%26b%3D2
```

Don't forget we use $this->auth->redirect() function for <b>unauthenticated</b> page views.

And you may want to create hidden redirect input element to your login screen.

```php
<?php echo form\open('/login/post', array('method' => 'POST')); ?>
<?php echo form\hidden('redirect', i_get('redirect')); ?>
```

So now you able to redirect user the redirect url. You need to check redirect input using i_get('redirect') function in your login.

### Checking Auth and Manually Redirect

------

```php
$this->auth->redirect('/login',  $urlencode = FALSE);
```

### Checking Auth is Ok and Redirect

------

Redirect user to <b>/dashboard</b> page for <b>authenticated</b> page views. Redirect page is a configurable option in auth.php config file.

```php
if($this->auth->check())
{
    redirect($this->auth->item('ok_url'));
}
It redirect request to /dashboard url as default.
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

#### $auth->select('field1, field3, field3 ..')

------ 

Creates database select string according to your table fields. This function store all selected fields to session container.

#### $auth->get($username = '', $password = '')

------

Creates sql query using username and password combination, if you not provide any username info it will look at $_REQUEST data.

#### $auth->set($fake_auth = false)

------

Authenticate the selected user and store user data to session container. If you provide $fake_auth = true, you can set authentication to user without database selection. For example we use this feature for who want to login with facebook connect button.

#### $auth->redirect($rediret_url = ' ', $urlencode = FALSE)

------

Check auth is fail and redirect it to provided url.

#### $auth->check()

------

Check user is authenticated if its ok it returns to TRUE otherwise FALSE.

#### $auth->data('key')

------

Retrieve authenticated user session data.

#### $auth->setData($key, $val)

------

Set extra data to authenticated user container.

#### $auth->unsetData($key)

------

Unset data from authenticated user container.

#### $auth->item($key)

------

Get auth config item.

#### $auth->setItem($key, $val)

------

Set auth config item.

#### $auth->setAuth($data = array())

------

Store auth data to session container.

#### $auth->logout()

------

Logout user, remove all auth data using destroy the sessions.