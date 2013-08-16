# Database 

## Quick Usage: PDO Examples <a name="quick-usage-pdo-examples"></a>

Why Obullo use PDO for database operations ? , download this [document](http://ilia.ws/files/quebec_PDO.pdf) it will give you some introductory information about PDO.

The following page contains example code showing how the database class is used. For complete details please read the individual pages describing each function.

### Initializing the Database Class <a name="initializing-the-database-class"></a>

------

The following code loads and initializes the database class based on your [configuration](#database-configuration-and-connect) settings:

```php
new Db\Db();
```

Once loaded the class is ready to be used as described below.

<strong>Note:</strong> If all your pages require database access you can connect automatically. See the connecting page for details.

### Standard Query With Multiple Results (Object Version)

------

```php
$query = $this->db->query('SELECT name, title, email FROM my_table');

foreach ($query->result() as $row)   // CODEIGNITER  DB FUNCTIONS ARE AVAILABLE
{
    echo $row->title;
    echo $row->name;
    echo $row->email;
}

echo 'Total Results: ' . $query->numRows();  // Pdo does not supported some database using 
row_count func via SELECT statement .(Mysql is ok.) 
```

The above <dfn>result()</dfn> function returns an array of <strong>objects</strong>. Example: $row->title

### Grabbing Database Object

------

```php
$database = new Db\Db(false);
$db = $database->connect();

print_r($db->get('users')->resultArray());
```

The above the example returns to Database Instance if you want to grab it.

### Standard Query With Multiple Results (Array Version)

------

```php
$query = $this->db->query('SELECT name, title, email FROM my_table');

foreach ($query->resultArray() as $row)
{
    echo $row['title'];
    echo $row['name'];
    echo $row['email'];
}
```

The above <dfn>resultArray()</dfn> function returns an array of standard array indexes. Example: $row['title']

### Testing for Results

------

If you run queries that might <strong>not</strong> produce a result, you are encouraged to test for a result first using the row() function:

```php
$query = $this->db->prep()    // pdo prepare switch 
->where('ip_address', '127.0.0.1')
->get('ob_sessions')    // from this table 
->exec();

if($query->row())
{
    $query = $query->exec();  // get cached query..
    $b = $query->resultArray();

    print_r($b);    // output array( ... )   
}
```

If your database support using row_count function via the SELECT statement you can do it like this .. (not recommended for portable applications..)

```php
$this->db->where('ip_address', '127.0.0.1')
->get('ob_sessions')    // from this table 

if($query->rowCount() > 0)
{
    $b = $query->resultArray();

    print_r($b);    // output array( ... )   
}
```

### Standard Query With Single Result

------

```php
$query = $this->db->query('SELECT name FROM my_table LIMIT 1');

$row = $query->row();

echo $row->name;
```

The above <dfn>row()</dfn> function returns an <strong>object</strong>. Example: $row->name

### Standard Query With Single Result (Array version)

------

```php
$query = $this->db->query('SELECT name FROM my_table LIMIT 1');

$row = $query->rowArray();

echo $row['name'];
```

The above <dfn>rowArray()</dfn> function returns an <strong>array</strong>. Example: $row['name']

### Standard Insert

------

```php
$sql = "INSERT INTO mytable (title, name)
        VALUES (".$this->db->escape($title).", ".$this->db->escape($name).")";

$affected_rows = $this->db->execQuery($sql);

// We use exec_query function for <b>native</b> insert, delete, update operations 

echo $affected_rows;
```

<strong>$this->db->escape()</strong> function just alias of <strong>PDO::quote($str, PDO::PARAM_STR);</strong> function.

We use <strong>execQuery()</strong> function for, insert, delete, update operations... It return to affected rows automatically.

### High Secure Insert

------

Dou you want to <strong>more security ?</strong>

```php
// HIGH SECURE NATIVE WAY ...  

$sql = "INSERT INTO mytable (title, number)
        VALUES (".$this->db->escape((string)$title).", ".$this->db->escape((int)$number).")";

$affected_rows = $this->db->execQuery($sql);

echo $affected_rows;
```

When you use native <strong>(string)</strong> ,<strong> (int)</strong> types front of your $variables it do filter automatically.

### CRUD ( Active Record ) Query

------

The [Active Record Pattern](#active-record-class) gives you a simplified means of retrieving data:

```php
$query = $this->db->get('table_name');

foreach ($query->result() as $row)
{
    echo $row->title;
}
```

The above <dfn>get()</dfn> function retrieves all the results from the supplied table. The [Active Record](#active-record-class) class contains a full compliment of functions for working with data.

### CRUD ( Active Record ) Insert

------

```php
$data = array(
               'title' => $title,
               'name' => $name,
               'date' => $date
            );

$affected_rows = $this->db->insert('mytable', $data);

// Query Output: INSERT INTO mytable (title, name, date) VALUES ($title, $name, $date) 
```

## Database Configuration <a name="database-configuration-and-connect"></a>

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
$database['db']['dbdriver'] = "mysql";
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
$database['db']['dbdriver'] = "mysql";
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
$database['db2']['dbdriver'] = "mysql";
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
$database['db3']['dbdriver'] = "";
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

### Active Record

-------

The [Active Record Class](#active-record-class) is globally enabled or disabled by setting the 'active_record' variable in the database configuration file to true/false (boolean). If you are not using the active record class, setting it to false will utilize fewer resources when the database classes are initialized.

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
namespace Ob;
Class Start extends Controller
{
    function __construct()
    {    
        new Db\Db();
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
function blog_dropdown()
{
    new Db\Db();   // If loader:database declared before in the controller
                          or model you don't need to write it again. 
    $ob = this();
    
    $ob->db->query(" .... ");
}
```

A <strong>model</strong> file.

```php
namespace Ob;
Class User extends Model
{
    function __construct()
    {
        new Db\Db();
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
$database['db2']['dbdriver'] = "mysql";
$database['db2']['dbh_port'] = "";
$database['db2']['char_set'] = "utf8";
$database['db2']['dsn']      = "";
$database['db2']['options']  = array();
```

Then you can connect to <samp>db2</samp> database just providing the db variable like this ..

```php
new Db\Db('db2');
  
$this->db2->query(" .... ");
```

Also you can manually pass database connectivity settings via the first parameter of your loader::database function like this.. :

```php
$config = array(
     'variable' => 'db3',
     'hostname' => 'localhost',
     'username' => 'root',
     'password' => '',
     'database' => 'test_db',
     'dbdriver' => 'mysql',
     'dbh_port' => '',
     'char_set' => 'utf8',
     'options'  => array( PDO::ATTR_PERSISTENT => true )
 );                                

 new Db\Db($config);

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

 new Db\Db($config);

$this->db2->query(  ....  );
```

<strong>Tip:</strong> You can reach your database connectivity settings by a  [common function](/docs/advanced#common-functions) called <samp>db()</samp>. Look at below the code.

```php
echo db('password', 'db2');   // output localhost 
```

This will give you password parameter of second database connection setting which is before defined in <dfn>app/config/database.php</dfn> file.

#### Returning to database object

If you want to grab the database object turn return object switch to true.

```php
$db = new Db\Db('db', true);  // provide your database variable
                              // and turn db return object switch to true.                        
$db->query(" ... "); 

$db2 = new Db\Db('db2', TRUE);  // second db
                                      
$db2->query(" ... ");
```

#### Using Your DB Class

You can close the database instantiate using first parameter to false. And if you extend to Database class you can instantiate it to manually. ( Look at [extending to core classes](https://github.com/obullo/obullo-2.0/tree/master/docs/advanced#extending-to-core-classes) for more details. )

```php
new Db\Db(false);

$db = new MY_Database();
$db = $db->connect('db');

$results = $db->query('SELECT * FROM ob_users')->resultArray();
print_r($results);
```

## Running and Escaping Queries

### Direct Query

------

To submit a query, use the following function:

```php
$this->db->query('YOUR QUERY HERE');
```

The <dfn>query()</dfn> function returns a database result object when "read" type queries are run, which you can use to show your results. When retrieving data you will typically assign the query to your own variable, like this:
$query = $this->db->query('YOUR QUERY HERE');
PDO Exec Query

This query type same as direct query just it returns to $affected_rows automatically. You should use exec_query function for INSERT, UPDATE, DELETE operations.
$affected_rows = $this->db->exec_query('INSERT QUERY'); 

echo $affected_rows;   //output  1
Escaping Queries

It's a very good security practice to escape your data before submitting it into your database. Obullo has three methods that help you do this:
$this->db->escape()
This function determines the data type so that it can escape only string data. It also automatically adds single quotes around the data and it can automatically determine data types. $sql = "INSERT INTO table (title) VALUES(".$this->db->escape((string)$title).")";

Supported data types: (int), (string), (boolean)
$this->escape_str();

This function escapes the data passed to it, regardless of type. Most of the time you'll use the above function rather than this one. Use the function like this:
$$sql = "INSERT INTO table (title) VALUES('".$this->db->escape_str($title)."')";
$this->db->escape_like()
This method should be used when strings are to be used in LIKE conditions so that LIKE wildcards ('%', '_') in the string are also properly escaped. $search = '20% raise';<br />
$sql = "SELECT id FROM table WHERE column LIKE '%".$this->db->escape_like($search)."%'";

Note: You don't need to $this->escape_like function when you use active record class because of active record class use auto escape foreach like condition.
$query = $this->db->select("*")
->like('article','%%blabla')
->or_like('article', 'blabla')
->get('articles');

echo $this->db->last_query();

// Output

However when you use query bind for like operators you must use $this->escape_like function like this
$this->db->prep()    // tell to db class use pdo prepare()
->select("*")
->like('article',":like")
->get('articles');

$value = "%%%some";
$this->db->exec(array(':like' => $this->db->escape_like($value)));
$this->db->fetch_all(assoc);

echo $this->db->last_query();

// Output
Query Bindings

Obullo offers PDO bindValue and bindParam functionality, using bind operations will help you for the performance and security:
Bind Types
Obullo Friendly Constant 	PDO CONSTANT 	Description
param_bool 	PDO::PARAM_BOOL 	Boolean
param_null 	PDO::PARAM_NULL 	NULL
param_int 	PDO::PARAM_INT 	Integer
param_str 	PDO::PARAM_STR 	String
param_lob 	PDO::PARAM_LOB 	Large Object Data (LOB)
Bind Value Example
$this->db->bind_value($paramater, $value, $data_type)
$this->db->prep();   // tell to db class use pdo prepare 
$this->db->query("SELECT * FROM articles WHERE article_id=:id OR link=:code");

$this->db->bind_value(':id', 1, param_int);  // Integer 
$this->db->bind_value(':code', 'i see dead people', param_str); // String      

$this->db->exec();  // execute query
$a = $this->db->fetch(assoc);

print_r($a);

The double dots in the query are automatically replaced with the values of bind_value functions.
Bind Param Example
$this->db->bind_param($paramater, $variable, $data_type, $data_length, $driver_options = array())
$this->db->prep();   // tell to db class use pdo prepare 
$this->db->query("SELECT * FROM articles WHERE article_id=:id OR link=:code");

$this->db->bind_param(':id', 1, param_int, 11);   // Integer 
$this->db->bind_param(':code', 'i see dead people', param_str, 20); // String (int Length)      

$this->db->exec();  // execute query
$a = $this->db->fetch(assoc);

print_r($a);

The double dots in the query are automatically replaced with the values of bind_param functions.

The secondary benefit of using binds is that the values are automatically escaped, producing safer queries. You don't have to remember to manually escape data; the engine does it automatically for you.

A Short Way ..
$this->db->prep();   
$query = $this->db->query("SELECT * FROM articles WHERE article_id=:id OR link=:code");

$query->bind_value(':id', 1, param_int);  
$query->bind_value(':code', 'i-see-dead-people', param_str); 

$query->exec();
$a = $query->fetch(assoc); 
print_r($a);
Automatically Bind Query
$this->db->prep();  
$this->db->query("SELECT * FROM articles WHERE article_id=:id OR link=:code");

$values[':id']   = '1';
$values[':code'] = 'i see dead people';

$this->db->exec($values);

$a = $this->db->fetch(assoc);
print_r($a);

Important: Obullo does not support Question Mark binding at this time. 