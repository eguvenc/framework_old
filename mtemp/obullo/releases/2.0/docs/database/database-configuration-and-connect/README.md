## Database Configuration 

### Server Requirements

------

Obullo use <strong>PDO (Php Data Objects)</strong> for database operations.<strong> Mysql</strong> and <strong>SQLite</strong> drivers is enabled by default as of PHP 5.1.0 and newer versions.If you want to use another Database driver you must enable related PDO Driver from your php.ini file.

Un-comment the PHP5 PDO database interface drivers in PHP.ini

```php
extension=php_pdo.dll
```

and un-comment your driver file

```php
extension=php_yourdriver.dll
```

on linux servers file extension is .so.

Look at for more details http://www.php.net/manual/en/pdo.installation.php

<strong>Tip:</strong> To edit your app/config/database.php , choose your <u>Obullo Connection Name</u> bottom of the table and change it like below the example.

```php
$database['db']['driver'] = "mysql";
```

### Supported Database Types

------

<table class="span9">
<thead>
<tr>
<th>PDO Driver Name</th>
<th>Obullo Connection Name</th>
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
<td>4D</td>
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

Obullo has a config file that lets you store your database connection values (username, password, database name, etc.). The config file is located at:

<kbd>app/config/database.php</kbd>

The config settings are stored in a multi-dimensional array with this prototype:

```php
$database['db']['hostname'] = "localhost";
$database['db']['username'] = "root";
$database['db']['password'] = "";
$database['db']['database'] = "example_db";
$database['db']['driver'] = "mysql";
$database['db']['dbh_port'] = "";
$database['db']['char_set'] = "utf8";
$database['db']['dsn']      = "";
$database['db']['options']  = array();
```

The reason we use a multi-dimensional array rather than a more simple one is to permit you to optionally store multiple sets of connection values.

If you want to add a second or third database connection <strong>copy/paste</strong> above the settings and change the <strong>'db'</strong> key name of your version you have choosen.Like this..

```php
// second database
$database['db2']['hostname'] = "localhost";
$database['db2']['username'] = "root";
$database['db2']['password'] = "";
$database['db2']['database'] = "example_db2";
$database['db2']['driver'] = "mysql";
$database['db2']['dbh_port'] = "";
$database['db2']['char_set'] = "utf8";
$database['db2']['dsn']      = "";
$database['db2']['options']  = array();
```

If you want to add <strong>dsn</strong> connection you don't need to provide some other parameters like this..

```php
// dsn
$database['db3']['hostname'] = "";
$database['db3']['username'] = "";
$database['db3']['password'] = "";
$database['db3']['database'] = "";
$database['db3']['driver'] = "";
$database['db3']['dbh_port'] = "";
$database['db3']['char_set'] = "utf8";
$database['db3']['dsn']      = "mysql:host=localhost;port=3307;dbname=test_db;username=root;password=1234;";
$database['db3']['options']  = array();
```

#### Database "options" Parameter

There is a global <strong>PDO options</strong> parameter in your database configuration. You can <strong>set connection attributes</strong> for each connection if you want. For example if you want to do  <strong>Persistent Connection</strong> you will do it like this..

```php
$database['db']['options']  = array( PDO::ATTR_PERSISTENT => true );
```

Persistent Connection will improve your database performance.

You can add more attributes in your option array like this..

```php
$database['db']['options']  = array( PDO::ATTR_PERSISTENT => false , 
                                     PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true );
```

<strong>Tip:</strong>You can learn more details about PDO Predefined Constants.

### CRUD ( Active Record )

-------

The CRUD ( Active Record ) Class is globally enabled or disabled by setting the 'active_record' variable in the database configuration file to true/false (boolean). If you are not using the active record class, setting it to false will utilize fewer resources when the database classes are initialized.

```php
$database['system']['active_record'] = true;
```

<strong>Note:</strong> that some Obullo classes such as Sessions require Active Records be enabled to access certain functionality.

#### Explanation of Values:

* _hostname_ - The hostname of your database server. Often this is "localhost".
* _username_ - The username used to connect to the database.
* _password_ - The password used to connect to the database.
* _database_ - The name of the database you want to connect to.
* _dbdriver_ - The database type. ie: mysql, postgres, odbc, etc. Must be specified in lower case.
* _dbh_port_ - The database port number.
* _char_set_ - The character set used in communicating with the database.
* _dsn_ - Data source name.If you want to use dsn, you will not need to supply other parameters.
* _options_ - Pdo set attribute options.

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
        new Db();
        parent::__construct();
    }
    
    public function index()
    {
        $this->db->query(" .... ");
    }
    
}
```

A <strong>helper</strong> file.

```php
function dropdown()
{
    new Db();   // If new Db() declared before in the controller
                          or model you don't need to write it again. 
    $obj = getInstance();
    
    $obj->db->query(" .... ");
}
```

A <strong>model</strong> file.

```php
Class User extends Model
{
    function __construct()
    {
        new Db();
        parent::__construct();
    }
    
    public function get_users()
    {
        $this->db->query(" .... ");
    }  
}
```

#### Multiple Connection

If your second database connection setting before defined in <dfn>app/config/database.php</dfn> file like this ..

```php
// second database
$database['db2']['hostname'] = "localhost";
$database['db2']['username'] = "root";
$database['db2']['password'] = "";
$database['db2']['database'] = "example_db2";
$database['db2']['driver']   = "mysql";
$database['db2']['dbh_port'] = "";
$database['db2']['char_set'] = "utf8";
$database['db2']['dsn']      = "";
$database['db2']['options']  = array();
```

Then you can connect to <samp>db2</samp> database just providing the db variable like this ..

```php
new Db('db2');
$this->db2->query(" .... ");
```

Also you can manually pass database connectivity settings via the first parameter of your <dfn>new Db($config)</dfn> function like this.. :

```php
$config = array(
     'variable' => 'db3',
     'hostname' => 'localhost',
     'username' => 'root',
     'password' => '',
     'database' => 'test_db',
     'driver' => 'mysql',
     'dbh_port' => '',
     'char_set' => 'utf8',
     'options'  => array( PDO::ATTR_PERSISTENT => true )
 );                                

 new Db($config);

$this->db3->query(  ....  );
```

#### Dsn Connection

If you are provide a <strong>dsn</strong> connection string you don't need to provide other parameters into <samp>$config</samp> data. This is the also same for the config file connection.

```php
$config = array(
     'variable' => 'db2',
     'char_set' => 'utf8',
     'dsn'      => 'mysql:host=localhost;port=3307;dbname=test_db;username=root;password=1234;'
 );

 new Db($config);

$this->db2->query(  ....  );
```

<strong>Tip:</strong> You can reach your database connectivity settings by a  common function called <samp>db()</samp>. Look at below the code.

```php
echo db('password', 'db2');   // output localhost 
```

This will give you password parameter of second database connection setting which is before defined in <dfn>app/config/database.php</dfn> file.
```

#### Grabbing the DB Instance

You can close the database instantiate using first parameter to false.

```php
$db = new Db(false);
$db = $db->connect('db');

$results = $db->query('SELECT * FROM ob_users')->resultArray();
print_r($results);
```