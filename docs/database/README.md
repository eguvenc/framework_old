# Database 

## Quick Usage: PDO Examples

Why Obullo use PDO for database operations ? , download this [document](http://ilia.ws/files/quebec_PDO.pdf) it will give you some introductory information about PDO.

The following page contains example code showing how the database class is used. For complete details please read the individual pages describing each function.

### Initializing the Database Class
------

The following code loads and initializes the database class based on your [configuration](https://github.com/obullo/obullo-2.0/tree/master/docs/database#database-configuration-and-connect) settings:
```php
new Db\Connect();
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

echo 'Total Results: ' . $query->num_rows();  // Pdo does not supported some database using 
row_count func via SELECT statement .(Mysql is ok.) 
```
The above <dfn>result()</dfn> function returns an array of <strong>objects</strong>. Example: $row->title

### Standard Query With Multiple Results (Array Version)
------

```php
$query = $this->db->query('SELECT name, title, email FROM my_table');

foreach ($query->result_array() as $row)
{
    echo $row['title'];
    echo $row['name'];
    echo $row['email'];
}
```
The above <dfn>result_array()</dfn> function returns an array of standard array indexes. Example: $row['title']

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
    $b = $query->result_array();

    print_r($b);    // output array( ... )   
}
```
If your database support using row_count function via the SELECT statement you can do it like this .. (not recommended for portable applications..)
```php
$this->db->where('ip_address', '127.0.0.1')
->get('ob_sessions')    // from this table 

if($query->row_count() > 0)
{
    $b = $query->result_array();

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

$row = $query->row_array();

echo $row['name'];
```
The above <dfn>row_array()</dfn> function returns an <strong>array</strong>. Example: $row['name']

### Standard Insert
------

```php
$sql = "INSERT INTO mytable (title, name)
        VALUES (".$this->db->escape($title).", ".$this->db->escape($name).")";

$affected_rows = $this->db->exec_query($sql);

// We use exec_query function for <b>native</b> insert, delete, update operations 

echo $affected_rows;
```
<strong>$this->db->escape()</strong> function just alias of <strong>PDO::quote($str, PDO::PARAM_STR);</strong> function.

We use <strong>exec_query()</strong> function for, insert, delete, update operations... It return to affected rows automatically.

### High Secure Insert
------

Dou you want to <strong>more security ?</strong>

```php
// HIGH SECURE NATIVE WAY ...  

$sql = "INSERT INTO mytable (title, number)
        VALUES (".$this->db->escape((string)$title).", ".$this->db->escape((int)$number).")";

$affected_rows = $this->db->exec_query($sql);

echo $affected_rows;
```
When you use native <strong>(string)</strong> ,<strong> (int)</strong> types front of your $variables it do filter automatically.

### CRUD ( Active Record ) Query
------
The [Active Record Pattern](https://github.com/obullo/obullo-2.0/tree/master/docs/database#active-record-class) gives you a simplified means of retrieving data:
```php
$query = $this->db->get('table_name');

foreach ($query->result() as $row)
{
    echo $row->title;
}
```
The above <dfn>get()</dfn> function retrieves all the results from the supplied table. The [Active Record](https://github.com/obullo/obullo-2.0/tree/master/docs/database#active-record-class) class contains a full compliment of functions for working with data.

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
$database['db']['dbdriver'] = "mysql";
```
### Supported Database Types
------

| PDO Driver Name | Obullo Connection Name | Database Name |
| ------------- |:-------------:| -----:|
| PDO_DBLIB | dblib / mssql / sybase / freetds | FreeTDS / Microsoft SQL Server / Sybase |
| PDO_FIREBIRD | firebird      |   Firebird/Interbase 6 |
| PDO_IBM | ibm / db2      |    IBM DB2 |
| PDO_MYSQL | mysql | MySQL 3.x/4.x/5.x |
| PDO_OCI  | oracle / (or alias oci)      |   Oracle Call Interface |
| PDO_ODBC | odbc      |    ODBC v3 (IBM DB2, unixODBC and win32 ODBC) |
| PDO_PGSQL | pgsql | PostgreSQL |
| PDO_SQLITE | sqlite / sqlite2 / sqlite3 |   SQLite 3 and SQLite 2 |
| PDO_4D | 4D |    4D |
| PDO_CUBRID | cubrid |    Cubrid |


Supported NOSQL Database Types

Driver Name	Connection Name	Database Name
mongodb	mongodb	MONGO
Obullo has a config file that lets you store your database connection values (username, password, database name, etc.). The config file is located at:

application/config/database.php

The config settings are stored in a multi-dimensional array with this prototype:

$database['db']['hostname'] = "localhost";
$database['db']['username'] = "root";
$database['db']['password'] = "";
$database['db']['database'] = "example_db";
$database['db']['dbdriver'] = "mysql";
$database['db']['dbh_port'] = "";
$database['db']['char_set'] = "utf8";
$database['db']['dsn']      = "";
$database['db']['options']  = array();
The reason we use a multi-dimensional array rather than a more simple one is to permit you to optionally store multiple sets of connection values.

If you want to add a second or third database connection copy/paste above the settings and change the 'db' key name of your version you have choosen.Like this..

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
If you want to add dsn connection you don't need to provide some other parameters like this..

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
Database "options" Parameter

There is a global PDO options parameter in your database configuration. You can set connection attributes for each connection if you want. For example if you want to do Persistent Connection you will do it like this..
$database['db']['options']  = array( PDO::ATTR_PERSISTENT => TRUE );
Persistent Connection will improve your database performance.

You can add more attributes in your option array like this..

$database['db']['options']  = array( PDO::ATTR_PERSISTENT => FALSE , 
                                     PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE );
Tip:You can learn more details about PDO Predefined Constants.
Active Record

The Active Record Class is globally enabled or disabled by setting the 'active_record' variable in the database configuration file to TRUE/FALSE (boolean). If you are not using the active record class, setting it to FALSE will utilize fewer resources when the database classes are initialized.

$database['system']['active_record'] = TRUE;
Note: that some Obullo classes such as Sessions require Active Records be enabled to access certain functionality.
Explanation of Values:

hostname - The hostname of your database server. Often this is "localhost".
username - The username used to connect to the database.
password - The password used to connect to the database.
database - The name of the database you want to connect to.
dbdriver - The database type. ie: mysql, postgres, odbc, etc. Must be specified in lower case.
dbh_port - The database port number.
char_set - The character set used in communicating with the database.
dsn - Data source name.If you want to use dsn, you will not need to supply other parameters.
options - Pdo set attribute options.
Note: Depending on what database platform you are using (MySQL, Postgres, etc.) not all values will be needed. For example, when using SQLite you will not need to supply a username or password, and the database name will be the path to your database file. The information above assumes you are using MySQL.
Database Connection

Standart Connection

Putting this code into your Controller or Model __construct() or inside to any function, enough for the current database connection.
A controller file.

Class Start extends Controller
{
    function __construct()
    {    
        loader::database();
        parent::__construct();
    }
    
    public function index()
    {
        $this->db->query(" .... ");
    }
    
}
A helper file.

function blog_dropdown()
{
    loader::database();   // If loader:database declared before in the controller
                          or model you don't need to write it again. 
    $ob = this();
    
    $ob->db->query(" .... ");
}
A model file.

Class Model_user extends Model
{
    function __construct()
    {
        loader::database();
        parent::__construct();
    }
    
    public function get_users()
    {
        $this->db->query(" .... ");
    }  
}
Multiple Connection

If your second database connection setting before defined in application/config/database.php file like this ..
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
Then you can connect to db2 database just providing the db variable like this ..

loader::database('db2');
  
$this->db2->query(" .... ");
Also you can manually pass database connectivity settings via the first parameter of your loader::database function like this.. :
$config = array(
     'variable' => 'db3',
     'hostname' => 'localhost',
     'username' => 'root',
     'password' => '',
     'database' => 'test_db',
     'dbdriver' => 'mysql',
     'dbh_port' => '',
     'char_set' => 'utf8',
     'options'  => array( PDO::ATTR_PERSISTENT => TRUE )
 );                                

 loader::database($config);

$this->db3->query(  ....  );
Dsn Connection

If you are provide a dsn connection string you don't need to provide other parameters into $config data. This is the also same for the config file connection.
$config = array(
     'variable' => 'db2',
     'char_set' => 'utf8',
     'dsn'      => 'mysql:host=localhost;port=3307;dbname=test_db;username=root;password=1234;'
 );

 loader::database($config);

$this->db2->query(  ....  );
Tip: You can reach your database connectivity settings by a common function called db_item(). Look at below the code.
echo db_item('password', 'db2');   // output localhost 
This will give you password parameter of second database connection setting which is before defined in application/config/database.php file.
Returning to database object

If you want to grab the database object turn return object switch to true.

$db = loader::database('db', TRUE);  // provide your database variable
                                     // and turn db return object switch to TRUE.
                                   
$db->query(" ... "); 

$db2 = loader::database('db2', TRUE);  // second db
                                      
$db2->query(" ... ");
Using Your DB Class

You can close the database instantiate using first parameter to FALSE. And if you extend to Database class you can instantiate it to manually. ( Look at extending to core classes for more details. )

loader::database(FALSE); 

$db = new MY_Database();
$db = $db->connect('db');

$results = $db->query('SELECT * FROM ob_users')->result_array();
print_r($results);
