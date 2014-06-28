## Sess Class

The Session (Sess) Class permits you to maintain a user's "state" and track their activity while they browse your site. The Sess Class stores session information for each user as serialized (using encrypted cookie) data in your database container. It can also store the session data containers like **(RDBMS, Redis, Memcache, Mongodb)** for added security, as this permits the session ID in the user's cookie to be matched against the stored session ID. By default it use Php Native Handler. 

If you choose to use the (RDBMS) database option you'll need to create the session table as indicated below.

**Note:** The Session **Database** driver generates its own session data, offering more flexibility for developers. However you can use the Php native handler for Redis or Memcached drivers.

### Initializing a Session Class

------

Sessions will typically run each page load, **so you need to call Sess class** for each page load if you need.

For the most part the session class will run unattended in the background, so simply initializing the sess class will cause it to read & update sessions.
If cookie not exists it will do the write operation just one time.

------

```php
new Sess;
$this->sess->method();
```
Once loaded, the Sess object will be available using: <dfn>$this->sess->method()</dfn>

You can pass the paramaters by manually like this.

```php
new Sess($params);
```

If you don't provide parameters then framework will load the configurations from sess.php file.

**Note:** Don't worry about it when you call the Sess object multiple times it do initialization just one time.

```php
new Sess;   // true driver loaded and session started. Session cookie writed to headers.
new Sess;   // false driver already loaded just do update for activity time.
new Sess;   // false driver already loaded just do update for activity time.
```

### Using Cache Driver ( Redis or Memcache )

------

Editing the <kbd>app/config/sess.php</kbd>  you can change the session driver which have the options : **Sess_Native, Sess_Database, Sess_Cache**.

```php
<?php
$sess = array(
    
    'cookie_name'     => 'frm_session',  // The name you want for the cookie
    'expiration'      => '7200',  // The number of SECONDS you want the session to last. "0" is no expiration.
    'expire_on_close' => false,   
    'encrypt_cookie'  => false,   // Whether to encrypt the cookie
    'driver'          => new Sess_Cache,      // Sess_Database  // Sess_Native
    'db'              => new Cache(array(    // null, // new Db // new Mongo_Db; Set any database object
                                           'driver'  => 'redis',
                                           'servers' => array(
                                                              'hostname' => '10.0.0.154',
                                                              'port'     => '6379',
                                                               // 'timeout'     => '2.5' // 2.5 sec timeout,
                                                               // just for redis cache
                                                              ),
                                           'auth' =>  'aZX0bjL',  // redis connection password
                                           'cache_path' =>  '/data/temp/cache/',
                                   )),
    
    'request'         => new Request,    // Set Request Object
    'table_name'      => 'frm_sessions', // The name of the session database table
    'match_ip'        => false,          // Whether to match the user's IP address
    'match_useragent' => true,        // Whether to match the User Agent 
    'time_to_update'  => 300        // How many seconds refreshing "Session" Information"
);

/* End of file sess.php */
/* Location: .app/config/debug/sess.php */
```

### Using Php Native Handler

```php
<?php

$sess = array(
    
    'cookie_name'     => 'frm_session', // The name you want for the cookie
    'expiration'      => 7200,          
    'expire_on_close' => false,
    'encrypt_cookie'  => false,         // Whether to encrypt the cookie

    'driver'          => new Sess_Native(array(
            
        'session.gc_divisor'      => 100,      // Configure garbage collection
        'session.gc_maxlifetime'  => 7200,
        'session.cookie_lifetime' => 0,
        'session.save_handler'    => 'redis',
        'session.save_path'       => 'tcp://10.0.0.154:6379?auth=aZX0bjL'

    )), 
    'request'         => new Request,        // Set Request Object
    'db'              => null,                  // null, // new Db, new Cache; // new Mongo_Db; 
    'table_name'      => 'frm_sessions',    // The name of the session database table
    'match_ip'        => false,         // Whether to match the user's IP address
    'match_useragent' => true,      // Whether to match the User Agent
    'time_to_update'  => 300        // How many seconds refreshing "Session" Information"
);
```

### Using ( RBDMS ) Databases


```php
<?php

$sess = array(
    
    'cookie_name'     => 'frm_session', // The name you want for the cookie
    'expiration'      => 7200,          
    'expire_on_close' => false,
    'encrypt_cookie'  => false,         // Whether to encrypt the cookie
    'driver'          => new Sess_Database, 
    'db'              => new Db,            // null, // new Db, new Cache; // new Mongo_Db; 
    'request'         => new Request,        // Set Request Object
    'table_name'      => 'frm_sessions',    // The name of the session database table
    'match_ip'        => false,         // Whether to match the user's IP address
    'match_useragent' => true,      // Whether to match the User Agent
    'time_to_update'  => 300        // How many seconds refreshing "Session" Information"
);
```

### Using Mongodb ( NoSql ) Database

```php
<?php

$sess = array(
    
    'cookie_name'     => 'frm_session', // The name you want for the cookie
    'expiration'      => 7200,          
    'expire_on_close' => false,
    'encrypt_cookie'  => false,         // Whether to encrypt the cookie
    'driver'          => new Sess_Database, 
    'db'              => new Mongo_Db,       // null, // new Db, new Cache; // new Mongo_Db; 
    'request'         => new Request,        // Set Request Object
    'table_name'      => 'frm_sessions',    // The name of the session database table
    'match_ip'        => false,         // Whether to match the user's IP address
    'match_useragent' => true,      // Whether to match the User Agent
    'time_to_update'  => 300        // How many seconds refreshing "Session" Information"
);
```


### Retrieving Session Data

------

Any piece of information from the session array is available using the following function:

```php
$this->sess->get('item');
```


Where <kbd>item</kbd> is the array index corresponding to the item you wish to fetch. For example, to fetch the session ID you will do this:

```php
$session_id = $this->sess->get('session_id');
```

**Note:** The function returns false (boolean) if the item you are trying to access does not exist.

### Adding Custom Session Data

------

A useful aspect of the session array is that you can add your own data to it and it will be stored in the user's cookie. Why would you want to do this? Here's one example:

Let's say a particular user logs into your site. Once authenticated, you could add their username and email address to the session cookie, making that data globally available to you without having to run a database query when you need it.

To add your data to the session array involves passing an array containing your new data to this function:

```php
$this->sess->set($array);
```
Where <samp>$array</samp> is an associative array containing your new data. Here's an example:

```php
$newdata = array(
                        'username'  => 'johndoe',
                        'email'     => 'johndoe@some-site.com',
                        'logged_in' => true
                       );

$this->sess->set($newdata);
```

```php
$this->sess->set('some_name', 'some_value');
```

### Removing Session Data

------

Just as $this->sess->set() can be used to add information into a session, $this->sess->remove() can be used to remove it, by passing the session key. For example, if you want to remove 'some_name' from your session information:

```php
$this->sess->remove('some_name');
```

This function can also be passed an associative array of items to unset.

```php
$array_items = array('username' => '', 'email' => '');

$this->sess->remove($array_items);
```

### Flashdata

------

Framework supports "flashData", or session data that will only be available for the next server request, and are then automatically cleared. These can be very useful, and are typically used for informational or status messages (for example: "record 2 deleted").

Note: Flash variables are prefaced with "flash_" so avoid this prefix in your own session names.

To add flashdata:

```php
$this->sess->setFlash('item', 'value');
```

You can also pass an array to $this->sess->setFlash(), in the same manner as $this->sess->set().

To read a flashdata variable:

```php
$this->sess->getFlash('item', $prefix = '' , $suffix = '');
```

If flash **data is empty** $this->sess->getFlash() function will return an empty string otherwise it will retun the flash data value with $prefix and $suffix codes.

```php
echo $this->sess->getFlash('item',  '<p class="example">' ,  '</p>');
```

If you find that you need to preserve a flashdata variable through an additional request, you can do so using the $this->sess->keepFlash() function.

```php
$this->sess->keepFlash('item');
```

### Saving Session Data to a Database

------

While the session data array stored in the user's cookie contains a Session ID, unless you store session data in a database there is no way to validate it. For some applications that requires little or no security, session ID validation may not be needed, but if your application requires security, validation is mandatory.

When session data is available in a database, every time a valid session is found in the user's cookie, a database query is performed to match it. If the session ID does not match, the session is destroyed. Session IDs can never be updated, they can only be generated when a new session is created.

In order to store sessions, you must first create a database table for this purpose. Here is the basic prototype (for MySQL) required by the session helper:

```php
CREATE TABLE IF NOT EXISTS  `frm_sessions` (
session_id varchar(40) DEFAULT '0' NOT NULL,
ip_address varchar(16) DEFAULT '0' NOT NULL,
user_agent varchar(50) NOT NULL,
last_activity int(10) unsigned DEFAULT 0 NOT NULL,
user_data text character set utf8 NOT NULL,
PRIMARY KEY (session_id)
);
```

**Note:** By default the table is called <kbd>frm_sessions</kbd>, but you can name it anything you want as long as you update the <kbd>app/config/sess.php</kbd> file so that it contains the name you have chosen. Once you have created your database table you can enable the database option in your sess.php file as follows:

```php
$sess['driver'] = 'database';
```

Once enabled, the Session helper will store session data in the DB.

Make sure you've specified the table name in your config file as well:

```php
$sess['table_name'] = 'frm_sessions';
```

**Note:** The Session helper has built-in garbage collection which clears out expired sessions so you do not need to write your own routine to do it.

### Destroying a Session

------

To clear the current session:

```php
$this->sess->destroy();
```

**Note:** This function should be the last one called, and even flash variables will no longer be available. If you want only some items to be destroyed and instead of all, use <kbd>$this->sess->remove()</kbd>.

### What is Session Data?

------

A *session*, as far as framework is concerned, is simply an array containing the following information:

* The user's unique Session ID (this is a statistically random string with very strong entropy, hashed with MD5 for portability, and regenerated (by default) every five minutes)
* The user's IP Address
* The user's User Agent data (the first 50 characters of the browser data string)
* The "last activity" time stamp.

The above data is stored in a cookie as a serialized array with this prototype:

```php
[array]
(
     'session_id'    => random hash,
     'ip_address'    => 'string - user IP address',
     'user_agent'    => 'string - user agent data',
     'last_activity' => timestamp
)
```

If you have the encryption option enabled, the serialized array will be encrypted before being stored in the cookie, making the data highly secure and impervious to being read or altered by someone. More info regarding encryption can be found here <kbd>( packages/encrypt/releases/0.0.1/ )</kbd>, although the Session helper will take care of initializing and encrypting the data automatically.

Note: Session cookies are only updated every five minutes by default to reduce processor load. If you repeatedly reload a page you'll notice that the "last activity" time only updates if five minutes or more has passed since the last time the cookie was written. This time is configurable by changing the <kbd>$sess['time_to_update']</kbd> line in your <kbd>app/config/sess.php</kbd> file.


### Session Preferences

------

You'll find the following Session related preferences in your <kbd>app/config/sess.php</kbd> file:


<table>
    <thead>
        <tr>
            <th>Preference</th>
            <th>Default</th>
            <th>Options</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>cookie_name</td>
            <td>frm_session</td>
            <td>None</td>
            <td>The name you want the session cookie saved as.</td>
        </tr>
        <tr>
            <td>expiration</td>
            <td>7200</td>
            <td>None</td>
            <td>The number of seconds you would like the session to last. The default value is 2 hours (7200 seconds). If you would like a non-expiring session set the value to zero: 0</td>
        </tr>
        <tr>
            <td>expire_on_close</td>
            <td>false</td>
            <td>None</td>
            <td>Whether to cause the session to expire automatically when the browser window is closed</td>
        </tr>
        <tr>
            <td>encrypt_cookie</td>
            <td>false</td>
            <td>true/false (boolean)</td>
            <td>Whether to encrypt the session data.</td>
        </tr>
        <tr>
            <td>driver</td>
            <td>native</td>
            <td>database | mongo | native</td>
            <td>Whether to save the session data to a database. If you want to use database driver you must create the table before enabling this option.</td>
        </tr>
        <tr>
            <td>db_var</td>
            <td>db</td>
            <td>Database Object Name</td>
            <td>You can change to database object used in session helper. Your database object must be loaded before using this option. (e.g. new Db('db2') ).</td>
        </tr>
        <tr>
            <td>table_name</td>
            <td>frm_sessions</td>
            <td>Any valid SQL table name</td>
            <td>The name of the session database table.</td>
        </tr>
        <tr>
            <td>time_to_update</td>
            <td>300</td>
            <td>Time in seconds</td>
            <td>This options controls how often the session helper will regenerate itself and create a new session id.</td>
        </tr>
        <tr>
            <td>match_ip</td>
            <td>false</td>
            <td>true/false (boolean)</td>
            <td>Whether to match the user's IP address when reading the session data. Note that some ISPs dynamically changes the IP, so if you want a non-expiring session you will likely set this to false.</td>
        </tr>
        <tr>
            <td>match_useragent</td>
            <td>true</td>
            <td>true/false (boolean)</td>
            <td>Whether to match the User Agent when reading the session data.</td>
        </tr>
    </tbody>
</table>

### Function Reference

------


#### $this->sess->set($new_data = mixed, $val = '', $prefix = '')

Stores a new session data to session container. You can send array data for first parameter.

#### $this->sess->get($key)

Gets stored session from session container.

#### $this->sess->getAllData()

Gets all stored session data.

#### $this->sess->remove($data = mixed, $prefix = '')

Unsets a stored session data from session container. You can send array data for first parameter.

#### $this->sess->setFlash($newdata = array(), $newval = '')

Add or changes flashdata, only available until the next request

#### $this->sess->keepFlash($key)

Keeps existing flashdata available to the next request.

#### $this->sess->getFlash()

Identifies flashdata as 'old' for removal when _flashdata_sweep() runs.

#### $this->sess->destroy()

Destroys the current session.