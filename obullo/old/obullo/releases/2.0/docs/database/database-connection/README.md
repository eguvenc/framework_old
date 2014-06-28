## Database Connection 

### Server Requirements

------

Db class uses <strong>PDO (Php Data Objects)</strong> (<kbd>Database Pdo</kbd> Component) for database operations. <strong>Mysql</strong> and <strong>SQLite</strong> drivers is enabled by default as of PHP 5.1.0 and newer versions.If you want to use another Database driver you must enable related PDO Driver from your php.ini file.

Un-comment the PDO database file pdo.ini

```php
extension=pdo.so
```

and un-comment your driver file pdo_mysql.ini

```php
extension=pdo_mysql.so
```

Look at for more details http://www.php.net/manual/en/pdo.installation.php

**Tip:** To edit your <kbd>app/debug/config.php</kbd> , choose your <u>Connection Name</u> bottom of the table and change it like below the example.

```php
$database['db']['driver'] = "mysql";
```

**Note:** This class uses <kbd>Database_Pdo</kbd> component defined in your package.json. You can <kbd>replace components</kbd> with third-party packages.


### Supported Database Types

------

<table class="span9">
<thead>
<tr>
<th>PDO Driver Name</th>
<th>Connection Name</th>
<th>Database Name</th>
</tr>
</thead>
<tbody>
<tr>
<td>PDO_DBLIB</td>
<td>dblib / mssql / sybase / freetds</td>
<td>FreeTDS / Microsoft SQL Server / Sybase</td>
</tr>
<tr>
<td>PDO_FIREBIRD</td>
<td>firebird</td>
<td>Firebird/Interbase 6</td>
</tr>
<tr>
<td>PDO_IBM</td>
<td>ibm / db2</td>
<td>IBM DB2</td>
</tr>
<tr>
<td>PDO_MYSQL</td>
<td>mysql</td>
<td>MySQL 3.x/4.x/5.x</td>
</tr>
<tr>
<td>PDO_OCI</td>
<td>oracle / (or alias oci)</td>
<td>Oracle Call Interface</td>
</tr>
<tr>
<td>PDO_ODBC</td>
<td>odbc</td>
<td>ODBC v3 (IBM DB2, unixODBC and win32 ODBC)</td>
</tr>
<tr>
<td>PDO_PGSQL</td>
<td>pgsql</td>
<td>PostgreSQL</td>
</tr>
<tr>
<td>PDO_SQLITE</td>
<td>sqlite / sqlite2 / sqlite3</td>
<td>SQLite 3 and SQLite 2</td>
</tr>
<tr>
<td>PDO_4D</td>
<td>4d</td>
<td>4D</td>
</tr>
<tr>
<td>PDO_CUBRID</td>
<td>cubrid</td>
<td>Cubrid</td>
</tr>
</tbody>
</table>

### Supported NOSQL Database Types

------

<table class="span9">
<thead>
<tr>
<th>Driver Name</th>
<th>Connection Name</th>
<th>Database Name</th>
</tr></thead>
<tbody>
<tr>
<td>mongodb</td>
<td>mongo</td>
<td>MONGO</td>
</tr>
</tbody>
</table>

Framework has a config file that lets you store your database connection values (username, password, database name, etc.). The config file is located in:

<kbd>app/debug/config.php</kbd>

The config settings are stored in a multi-dimensional array with this prototype:

```php
'database' => array(
    'db' => array(
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'example_db',
        'driver'   => 'mysql',
        'prefix'   => '',
        'dbh_port' => '',
        'char_set' => 'utf8',
        'dsn'      => '',
        'options'  => array()
        )
 ),
```

The reason we use a multi-dimensional array rather than a more simple one is to permit you to optionally store multiple sets of connection values.

If you want to add a second or third database connection <strong>copy/paste</strong> above the settings and change the <strong>'db'</strong> key name of your version you have choosen.Like this..

```php


'database' => array(
    'db' => array(
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'example_db',
        'driver'   => 'mysql',
        'prefix'   => '',
        'dbh_port' => '',
        'char_set' => 'utf8',
        'dsn'      => '',
        'options'  => array()
        ),
    'db2' => array(
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'example_db2',
        'driver'   => 'mysql',
        'prefix'   => '',
        'dbh_port' => '',
        'char_set' => 'utf8',
        'dsn'      => '',
        'options'  => array()
        )
 ),

```

If you want to add <strong>dsn</strong> connection you don't need to provide some other parameters like this..

```php
    'db' => array(
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'example_db',
        'driver'   => 'mysql',
        'prefix'   => '',
        'dbh_port' => '',
        'char_set' => 'utf8',
        'dsn'      => "mysql:host=localhost;port=3307;dbname=test_db;username=root;password=1234;",
        'options'  => array()
        ),
```

#### Database "options" Parameter

There is a global <strong>PDO options</strong> parameter in your database configuration. You can <strong>set connection attributes</strong> for each connection. if you want to <strong>Persistent Connection</strong> you can do it like.

```php
'options'  => array( PDO::ATTR_PERSISTENT => true );
```
You can add more attributes in your option array like this.

```php
'options' => array( PDO::ATTR_PERSISTENT => false , PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true );
```

<strong>Tip:</strong>You can learn more details about PDO Predefined Constants.

#### Explanation of Values:

* hostname - The hostname of your database server. Often this is "localhost".
* username - The username used to connect to the database.
* password - The password used to connect to the database.
* database - The name of the database you want to connect to.
* dbdriver - The database type. ie: mysql, postgres, odbc, etc. Must be specified in lower case.
* dbh_port - The database port number.
* char_set - The character set used in communicating with the database.
* dsn - Data source name.If you want to use dsn, you will not need to supply other parameters.
* options - Pdo set attribute options.

<strong>Note:</strong> Depending on what database platform you are using (MySQL, Postgres, etc.) not all values will be needed. For example, when using SQLite you will not need to supply a username or password, and the database name will be the path to your database file. The information above assumes you are using MySQL.

### Database Connection

------

#### Standart Connection

Putting this code into your Controller or Model <samp>__construct()</samp> or inside to any function, enough for the current database connection.
A controller file.

```php
Class Start extends Controller
{
    function __construct()
    {    
        parent::__construct();
        new Db();
    }
    
    public function index()
    {
        $this->db->query(" .... ");
    }
    
}
```

Using Db object in functions

```php
function dropdown()
{
    new Db();   // If db object available it use the old instance.

    $obullo = getInstance();
    $obullo->db->query(" .... ");
}
```

In your <strong>model</strong> file.

```php
new Model('user', '', 'db2');  // third parameter sets the database variable
```

#### Multiple Connection

If your second database connection setting before defined in <kbd>app/debug/config.php</kbd> file like below,

```php
    'db2' => array(
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'example_db',
        'driver'   => 'mysql',
        'prefix'   => '',
        'dbh_port' => '',
        'char_set' => 'utf8',
        'dsn'      => '',
        'options'  => array()
        ),
```

then you can connect to <samp>db2</samp> database just providing the db variable.

```php
new Db('db2');
$this->db2->query(" .... ");
```

Also you can manually pass database connectivity settings via the first parameter of your <kbd>new Db($config)</kbd> function like this.. :

```php
$config = array(
     'variable' => 'db3',
     'hostname' => 'localhost',
     'username' => 'root',
     'password' => '',
     'database' => 'test_db',
     'driver'   => 'mysql',
     'dbh_port' => '',
     'char_set' => 'utf8',
     'options'  => array( PDO::ATTR_PERSISTENT => true )
 );                                

new Db($config);

$this->db3->query(  ....  );
```

#### Dsn Connection

If you provide a <strong>dsn</strong> connection string you don't need to provide other parameters into <samp>$config</samp> data. This is the also same for the config file connection.

```php
$config = array(
     'variable' => 'db2',
     'char_set' => 'utf8',
     'dsn'      => 'mysql:host=localhost;port=3307;dbname=test_db;username=root;password=1234;'
 );

 new Db($config);

$this->db2->query(  ....  );
```

<strong>Tip:</strong> You can reach your database connectivity settings by common function <b>getConfig()</b>.

```php
$config = getConfig();  

echo $config['database']['db2']['hostname']; // gives localhost 
```

This gives you the password parameter of second database setting which is defined in <kbd>app/debug/config.php</kbd> file.