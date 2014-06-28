## Auth Class

------

Auth Class provides a lightweight and simple auth implementation for user authentication management.

### Initializing the Class

------

```php
new Auth;
$this->auth->method();
```

Once loaded, the Auth object will be available using: $this->auth->method();

### Configuring Auth Options

------

You can set authentication options in <dfn>app/config/auth.php</dfn> file.

```php
<?php

/*
|--------------------------------------------------------------------------
| Auth Package Configuration
|--------------------------------------------------------------------------
| Configure auth library options
|
*/
$auth = array(
    'session_prefix'     => 'auth_',   // Set a prefix to prevent collisions with original session object.
    'allow_login'        => true,      // Whether to allow logins to be performed on login form.
    'regenerate_sess_id' => false,     // Set to true to regenerate the session id on every page load or leave as false to regenerate only upon new login.
);

/*
|--------------------------------------------------------------------------
| Dependeny Injection Objects
|--------------------------------------------------------------------------
| Configure dependecies
|
*/
$auth['database']  = 'db';           // Your database variable which is strored in the controller name e.g. getInstance()->{db}
$auth['session']   = function () {   // Session Dependency
    return new Sess;                 // Start the sessions
};
$auth['algorithm'] = function () {   // Whether to use "bcrypt" or another custom object 
    return new Bcrypt;               // return 'sha256'    // if you don't want to use Bcrypt object return "sha1", "sha256" or any hash type ,.
};

/*
|--------------------------------------------------------------------------
| Dependeny Injection Methods
|--------------------------------------------------------------------------
| Configure Methods
|
*/
$auth['extend']['hashPassword']   = function ($password) use ($auth) {   //  Whether to use "bcrypt" "sha1","sha256","sha512" type hashes. ( Do not use md5 )
    $algorithm = $auth['algorithm']();
    if (is_object($algorithm)) {
        return $algorithm->hashPassword($password);   // returns to hashed string
    }
    return hash($algorithm, $password);  // Default Native hash
};

$auth['extend']['verifyPassword'] = function ($password, $hashedPassword) use ($auth) {
    $algorithm = $auth['algorithm']();                               
    if (is_object($algorithm)) {                                        // Initialize your algorithm class
        return $algorithm->verifyPassword($password, $hashedPassword);  // Returns "true" if password verify success otherwise "false"
    }
    return ($hashedPassword === hash($algorithm, $password));     // Default Native hash
};

/* End of file auth.php */
/* Location: .app/config/auth.php */
```

### Sending Data to Auth Query

------

```php
<?php
new Db;
new Auth;

$email    = $this->post->get('email');     // identifier input
$password = $this->post->get('password');  // password input

$row = $this->auth->query($password, function() use($email){

    $this->db->prep();
    $this->db->select('id, username, password, email');
    $this->db->where('email', ':email');
    $this->db->get('users');
    $this->db->bindParam(':email', $email, PARAM_STR, 60); // String (int Length),
    $this->db->exec();

    $row = $this->db->getRow();

    if($row !== false)
    {
        //-------- Set password for verify ------------//

        $this->setPassword($row->user_password); 

        //------------------------------------------------
    }

    return $row;   // return to database row
});

if($row !== false) // validate the auth !
{
    $this->auth->authorize(function() use($row){    // Authorize to user

        $this->setIdentity('user_username', $row->user_username); // Set user data to auth container
        $this->setIdentity('user_email', $row->user_email);
        $this->setIdentity('user_id', $row->user_id);
    });

    $this->url->redirect('/home'); // Success redirect
}

$this->url->redirect('/login');
```

### Checking Identity

------

```php
<?php
new Auth;

if($this->auth->hasIdentity())
{
     echo 'Great you are authorized to view this page !'; 
}
```

### Checking Identity and Redirect

------

Redirect user to <kbd>dashboard_url</kbd> using url object.

```php
<?php

if($this->auth->hasIdentity())
{
    $this->url->redirect('/dashboard');
}
```

### Getting User Data From Sessions

------

```php
$this->auth->getIdentity('username');
```

### Getting All User Identity Data

------

```php
print_r($this->auth->getAllData());  //  gives array('auth_identity' => 'yes', 'auth_username' => 'John', 'auth_active' => 1);
```

### Logout User

------

This function will destroy the user identity data.

```php
$this->auth->clearIdentity();
```

### Function Reference

------

#### $this->auth->query($username = '', $password = '')

Tries authentication attempt and do sql query using username and password combination, if no data provided as parameter , it will look at the $_POST data.

#### $this->auth->authorize()

Authorizes and gives identity to the user.

#### $this->auth->getRow()

Gets authorized user query result in object.

#### $this->auth->setIdentity($key, $val)

Checks the identity of the user.

#### $this->auth->hasIdentity()

Checks if the user is authorized, if so, it returns to true, otherwise false.

#### $this->auth->getIdentity('key')

Retrieves the authenticated user identity data.

#### $this->auth->setIdentity($key, $val)

Sets user identity data.

#### $this->auth->removeIdentity($key)

Removes identity data from identity container.

#### $this->auth->clearIdentity()

Logs out user, destroys all identity data. This method <kbd>does not destroy</kbd> the user <kbd>sessions</kbd>. It will just remove authorization and identity data of the user.

#### $this->auth->getItem($key)

Gets auth config item.

#### $this->auth->setItem($key, $val)

Sets auth config item.

#### $this->auth->getAllData()

Gets all identity data from user session.