## Session Helper

Obullo has a **simple procedural** session implementation. The Session Helper permits you maintain a user's "state" and track their activity while they browse your site. The Session Helper stores session information for each user as serialized (and optionally encrypted) data in a cookie. It can also store the session data in a database table **(RDBMS, memcached or mongodb)** for added security, as this permits the session ID in the user's cookie to be matched against the stored session ID. By default only the cookie is saved. If you choose to use the database option you'll need to create the session table as indicated below.

**Note:** The Session **Database and Cookie** driver does **not** utilize native PHP sessions. It generates its own session data, offering more flexibility for developers.

### Loading this Helper

------

This helper is loaded using the following code:

```php
new sess\start();
```

### Changing Driver

------

Using edit to <samp>app/config/config.php</samp> you can change the session driver Obullo has 4 session drivers called : **Native, Cookie, Database, Mongodb**.

```php
$config['sess_driver']           = 'native';  // cookie | database | mongodb
```

### Initializing a Session

------

Sessions will typically run globally with each page load, so the session helper must either be initialized in your [controller](/docs/general/#controllers) constructors, or it can be [auto-load](/docs/advanced/#auto-loading) by the system.

For the most part the session helper will run unattended in the background, so simply initializing the helper file will cause it to read, create, and update sessions.

```php
sess_start();
```

You can pass the paramaters by manually like this.

```php
$params['sess_db_var'] = 'db2';
$params['sess_db_driver'] = 'database';

sess_start($params);
```

If you don't provide parameters then Obullo will load the configurations from config.php file.

### How do Sessions work?

------

When a page is loaded, the session helper will check to see if valid session data exists in the user's session cookie. If sessions data does **not** exist (or if it has expired) a new session will be created and saved in the cookie. If a session does exist, its information will be updated and the cookie will be updated. With each update, the session_id will be regenerated.

It's important for you to understand that once initialized, the Session helper runs automatically. There is nothing you need to do to cause the above behavior to happen. You can, as you'll see below, work with session data or even add your own data to a user's session, but the process of reading, writing, and updating a session is automatic.

*Critical* If you intend to use session helper file in the all application you can load and declare **sess_start** function using **autoload** and **autorun** files look at below the example.
Autoloading sessions

```php
$autoload['helper']     = array('sess', 'vi', 'html', 'url');
```

Auto running sessions

```php
$autorun['function']['sess\start']   = array();
```

Look at this section for more details about [auto-loading and auto-running](/docs/advanced/#auto-loading).

If you don't want declare sess_func by globally you can use manually where do you need it. Sometimes if you are **not carefull** you may declare **sess\start(); **function more than one time in the application. So don't worry about it when you declare this function multiple times it will simply return to **FALSE**.

```php
sess\start();  // TRUE driver loaded and session started.
sess\start();  // FALSE driver already loaded and session started before.
sess\start();  // FALSE driver already loaded and session started before. 
sess\start();  // FALSE driver already loaded and session started before. 

/*
*
*
*/
```

*Tip:* You should declare this function at the top forexample in **__construct()** function.

### What is Session Data?

------

A *session*, as far as Obullo is concerned, is simply an array containing the following information:

- The user's unique Session ID (this is a statistically random string with very strong entropy, hashed with MD5 for portability, and regenerated (by default) every five minutes)
- The user's IP Address
- The user's User Agent data (the first 50 characters of the browser data string)
- The "last activity" time stamp.

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

If you have the encryption option enabled, the serialized array will be encrypted before being stored in the cookie, making the data highly secure and impervious to being read or altered by someone. More info regarding encryption can be [found here](/ob/encrypt/releases/0.0.1/), although the Session helper will take care of initializing and encrypting the data automatically.

Note: Session cookies are only updated every five minutes by default to reduce processor load. If you repeatedly reload a page you'll notice that the "last activity" time only updates if five minutes or more has passed since the last time the cookie was written. This time is configurable by changing the $config['time_to_update'] line in your <dfn>app/config/config.php</dfn> file.

### Retrieving Session Data

------

Any piece of information from the session array is available using the following function:

```php
sess\get('item');
```


Where <kbd>item</kbd> is the array index corresponding to the item you wish to fetch. For example, to fetch the session ID you will do this:

```php
$session_id = sess\get('session_id');
```

**Note:** The function returns FALSE (boolean) if the item you are trying to access does not exist.

### Adding Custom Session Data

------

A useful aspect of the session array is that you can add your own data to it and it will be stored in the user's cookie. Why would you want to do this? Here's one example:

Let's say a particular user logs into your site. Once authenticated, you could add their username and email address to the session cookie, making that data globally available to you without having to run a database query when you need it.

To add your data to the session array involves passing an array containing your new data to this function:

```php
sess\set($array);
```
Where <samp>$array</samp> is an associative array containing your new data. Here's an example:

```php
$newdata = array(
                           'username'  => 'johndoe',
                           'email'     => 'johndoe@some-site.com',
                           'logged_in' => TRUE
                       );

sess\set($newdata);
```

**Important:** Once you used at the top **sess\start()** function you don't need to use again.
If you want to add userdata one value at a time, set() also supports this syntax.

```php
sess\set('some_name', 'some_value');
```

**Critical:** Cookies can only hold **4KB** of data, so be careful not to exceed the capacity. The encryption process in particular produces a longer data string than the original so keep careful track of how much data you are storing.

### Removing Session Data

------

Just as sess\set() can be used to add information into a session, sess\remove() can be used to remove it, by passing the session key. For example, if you wanted to remove 'some_name' from your session information:

```php
sess\remove('some_name');
```

This function can also be passed an associative array of items to unset.

```php
$array_items = array('username' => '', 'email' => '');

sess\remove($array_items);
```

### Flashdata

------

Obullo supports "flashdata", or session data that will only be available for the next server request, and are then automatically cleared. These can be very useful, and are typically used for informational or status messages (for example: "record 2 deleted").

Note: Flash variables are prefaced with "flash_" so avoid this prefix in your own session names.

To add flashdata:

```php
sess\setFlash('item', 'value');
```

You can also pass an array to sess\setFlash(), in the same manner as sess\set().

To read a flashdata variable:

```php
sess\getFlash('item', $prefix = '' , $suffix = '');
```

If flash **data empty** sess\getFlash() function will return an empty string otherwise it will retun the flash data value with $prefix and $suffix codes.

```php
echo sess\getFlash('item',  '<p class="example">' ,  '</p>');
```

If you find that you need to preserve a flashdata variable through an additional request, you can do so using the sess\keepFlash() function.

```php
sess\keepFlash('item');
```

### Saving Session Data to a Database

------

While the session data array stored in the user's cookie contains a Session ID, unless you store session data in a database there is no way to validate it. For some applications that require little or no security, session ID validation may not be needed, but if your application requires security, validation is mandatory.

When session data is available in a database, every time a valid session is found in the user's cookie, a database query is performed to match it. If the session ID does not match, the session is destroyed. Session IDs can never be updated, they can only be generated when a new session is created.

In order to store sessions, you must first create a database table for this purpose. Here is the basic prototype (for MySQL) required by the session helper:

```php
CREATE TABLE IF NOT EXISTS  `ob_sessions` (
session_id varchar(40) DEFAULT '0' NOT NULL,
ip_address varchar(16) DEFAULT '0' NOT NULL,
user_agent varchar(50) NOT NULL,
last_activity int(10) unsigned DEFAULT 0 NOT NULL,
user_data text character set utf8 NOT NULL,
PRIMARY KEY (session_id)
);
```

**Note:** By default the table is called <dfn>ob_sessions</dfn>, but you can name it anything you want as long as you update the <samp>app/config/config.php</samp> file so that it contains the name you have chosen. Once you have created your database table you can enable the database option in your config.php file as follows:

```php
$config['sess_driver'] = 'database';
```

Once enabled, the Session helper will store session data in the DB.

Make sure you've specified the table name in your config file as well:

```php
$config['sess_table_name'] = 'ob_sessions";
```

**Note:** The Session helper has built-in garbage collection which clears out expired sessions so you do not need to write your own routine to do it.

### Destroying a Session

------

To clear the current session:

```php
sess\destroy();
```

**Note:** This function should be the last one called, and even flash variables will no longer be available. If you only want some items destroyed and not all, use <dfn>sess\remove()</dfn>.

### Session Preferences

------

You'll find the following Session related preferences in your <samp>app/config/config.php</samp> file:


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
            <td>ob_session</td>
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
            <td>FALSE</td>
            <td>None</td>
            <td>If set TRUE all sessions will be destroy when users close the browser.</td>
        </tr>
        <tr>
            <td>encrypt_cookie</td>
            <td>FALSE</td>
            <td>TRUE/FALSE (boolean)</td>
            <td>Whether to encrypt the session data.</td>
        </tr>
        <tr>
            <td>driver</td>
            <td>cookie</td>
            <td>cookie | database | mongo | native</td>
            <td>Whether to save the session data to a database. If you want to use database driver you must create the table before enabling this option.</td>
        </tr>
        <tr>
            <td>db_var</td>
            <td>db</td>
            <td>Database Object Name</td>
            <td>You can change to database object used in session helper. Your database object must be loaded before using this option. (e.g. loader::database('db2') ).</td>
        </tr>
        <tr>
            <td>table_name</td>
            <td>ob_sessions</td>
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
            <td>FALSE</td>
            <td>TRUE/FALSE (boolean)</td>
            <td>Whether to match the user's IP address when reading the session data. Note that some ISPs dynamically changes the IP, so if you want a non-expiring session you will likely set this to FALSE.</td>
        </tr>
        <tr>
            <td>match_useragent</td>
            <td>TRUE</td>
            <td>TRUE/FALSE (boolean)</td>
            <td>Whether to match the User Agent when reading the session data.</td>
        </tr>
    </tbody>
</table>

### Function Reference

------

#### sess\start($params = array())

Start the sessions.

#### sess\set($new_data = mixed, $val = '', $prefix = '')

Store a new session data to session container. You can send array data for first parameter.

#### sess\get($key)

Get stored session from session container.

#### sess\alldata()

Get stored all session data.

#### sess\remove($data = mixed, $prefix = '')

Unset a stored session data from session container. You can send array data for first parameter.

#### sess\setFlash($newdata = array(), $newval = '')

Add or change flashdata, only available until the next request

#### sess\keepFlash($key)

Keeps existing flashdata available to next request.

#### sess\getFlash()

Identifies flashdata as 'old' for removal when _flashdata_sweep() runs.

#### sess\destroy()

Destroy the current session.

