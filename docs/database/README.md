# Database 

## Quick Usage: PDO Examples <a name="quick-usage-pdo-examples"></a>

Why Obullo use PDO for database operations ? , download this [document](http://ilia.ws/files/quebec_PDO.pdf) it will give you some introductory information about PDO.

The following page contains example code showing how the database class is used. For complete details please read the individual pages describing each function.

### Initializing the Database Class <a name="initializing-the-database-class"></a>

------

The following code loads and initializes the database class based on your [configuration](#database-configuration-and-connect) settings:

```php
new Db();
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
rowCount func via SELECT statement .(Mysql is ok.) 
```

The above <dfn>result()</dfn> function returns an array of <strong>objects</strong>. Example: $row->title

### Grabbing Database Object

------

```php
$database = new Db(false);
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

If your database support using rowCount function via the SELECT statement you can do it like this .. (not recommended for portable applications..)

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

// We use execQuery function for <b>native</b> insert, delete, update operations 

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
function blog_dropdown()
{
    new Db();   // If new Db() declared before in the controller
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
$database['db2']['driver'] = "mysql";
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

<strong>Tip:</strong> You can reach your database connectivity settings by a  [common function](/docs/advanced#common-functions) called <samp>db()</samp>. Look at below the code.

```php
echo db('password', 'db2');   // output localhost 
```

This will give you password parameter of second database connection setting which is before defined in <dfn>app/config/database.php</dfn> file.

#### Returning to database object

If you want to grab the database object turn return object switch to true.

```php
$db = new Db('db', true);  // provide your database variable
                              // and turn db return object switch to true.                        
$db->query(" ... "); 

$db2 = new Db('db2', TRUE);  // second db
                                      
$db2->query(" ... ");
```

#### Using Your DB Class

You can close the database instantiate using first parameter to false. And if you extend to Database class you can instantiate it to manually. ( Look at [extending to core classes](https://github.com/obullo/obullo-2.0/tree/master/docs/advanced#extending-to-core-classes) for more details. )

```php
new Db(false);

$db = new MY_Database();
$db = $db->connect('db');

$results = $db->query('SELECT * FROM ob_users')->resultArray();
print_r($results);
```

## Running and Escaping Queries <a name="running-and-escaping-queries"></a>

### Direct Query

------

To submit a query, use the following function:

```php
$this->db->query('YOUR QUERY HERE');
```

The <dfn>query()</dfn> function returns a database result **object** when "read" type queries are run, which you can use to [show your results](#generating-query-results). When retrieving data you will typically assign the query to your own variable, like this:

```php
$query = $this->db->query('YOUR QUERY HERE');
```

### PDO Exec Query

------

This query type same as direct query just it returns to $affected_rows automatically. You should use **execQuery** function for INSERT, UPDATE, DELETE operations.

```php
$affected_rows = $this->db->execQuery('INSERT QUERY'); 

echo $affected_rows;   //output  1
```

### Escaping Queries

------

It's a very good security practice to escape your data before submitting it into your database. Obullo has three methods that help you do this:

#### $this->db->escape()

This function determines the data type so that it can escape only string data. It also automatically adds single quotes around the data and it can automatically determine data types. 

```php
$sql = "INSERT INTO table (title) VALUES(".$this->db->escape((string)$title).")";
```

Supported data types: <samp>(int), (string), (boolean)</samp>

#### $this->escapeStr();

This function escapes the data passed to it, regardless of type. Most of the time you'll use the above function rather than this one. Use the function like this:

```php
$sql = "INSERT INTO table (title) VALUES('".$this->db->escapeStr($title)."')";
```

#### $this->db->escapeLike()
This method should be used when strings are to be used in LIKE conditions so that LIKE wildcards ('%', '_') in the string are also properly escaped. 

```php
$search = '20% raise';<br />
$sql = "SELECT id FROM table WHERE column LIKE '%".$this->db->escapeLike($search)."%'";
```

**Note:** You don't need to **$this->escapeLike** function when you use active record class because of [active record class](#active-record-class) use auto escape foreach like condition.

```php
$query = $this->db->select("*")
->like('article','%%blabla')
->orLike('article', 'blabla')
->get('articles');

echo $this->db->lastQuery();

// Output
```

However when you use **query bind** for **like operators** you must use **$this->escapeLike** function like this

```php
$this->db->prep()    // tell to db class use pdo prepare()
->select("*")
->like('article',":like")
->get('articles');

$value = "%%%some";
$this->db->exec(array(':like' => $this->db->escapeLike($value)));
$this->db->fetchAll(assoc);

echo $this->db->lastQuery();

// Output
```

### Query Bindings

------

Obullo offers PDO bindValue and bindParam functionality, using bind operations will help you for the performance and security:

#### Bind Types

<table>
    <thead>
        <tr>
            <th>Obullo Friendly Constant</th>
            <th>PDO CONSTANT</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>PARAM_BOOLl</td>
            <td>PDO::PARAM_BOOL</td>
            <td>Boolean</td>
        </tr>
        <tr>
            <td>PARAM_NULL</td>
            <td>PDO::PARAM_NULL</td>
            <td>NULL</td>
        </tr>
        <tr>
            <td>PARAM_INT</td>
            <td>PDO::PARAM_INT</td>
            <td>String</td>
        </tr>
        <tr>
            <td>PARAM_STR</td>
            <td>PDO::PARAM_STR</td>
            <td>Integer</td>
        </tr>
        <tr>
            <td>PARAM_LOB</td>
            <td>PDO::PARAM_LOB</td>
            <td>Large Object Data (LOB)</td>
        </tr>
    </tbody>
</table>

#### Bind Value Example

##### $this->db->bindValue($paramater, $value, $data_type)

```php
$this->db->prep();   // tell to db class use pdo prepare 
$this->db->query("SELECT * FROM articles WHERE article_id=:id OR link=:code");

$this->db->bindValue(':id', 1, PARAM_INT);  // Integer 
$this->db->bindValue(':code', 'i see dead people', PARAM_STR); // String      

$this->db->exec();  // execute query
$a = $this->db->fetch(assoc);

print_r($a);
```

The **double dots** in the query are automatically replaced with the values of **bindValue** functions.

#### Bind Param Example

##### $this->db->bindParam($paramater, $variable, $data_type, $data_length, $driver_options = array())

```php
$this->db->prep();   // tell to db class use pdo prepare 
$this->db->query("SELECT * FROM articles WHERE article_id=:id OR link=:code");

$this->db->bindParam(':id', 1, PARAM_INT, 11);   // Integer 
$this->db->bindParam(':code', 'i see dead people', PARAM_STR, 20); // String (int Length)      

$this->db->exec();  // execute query
$a = $this->db->fetch(assoc);

print_r($a);
```
The **double dots** in the query are automatically replaced with the values of **bindParam** functions.

The secondary benefit of using binds is that the values are automatically escaped, producing safer queries. You don't have to remember to manually escape data; the engine does it automatically for you.

#### A Short Way ..

```php
$this->db->prep();   
$query = $this->db->query("SELECT * FROM articles WHERE article_id=:id OR link=:code");

$query->bindValue(':id', 1, PARAM_INT);  
$query->bindValue(':code', 'i-see-dead-people', PARAM_STR); 

$query->exec();
$a = $query->fetch(assoc); 
print_r($a);
```

#### Automatically Bind Query

```php
$this->db->prep();  
$this->db->query("SELECT * FROM articles WHERE article_id=:id OR link=:code");

$values[':id']   = '1';
$values[':code'] = 'i see dead people';

$this->db->exec($values);

$a = $this->db->fetch(assoc);
print_r($a);
```
**Important:** Obullo does not support Question Mark binding at this time.

 ## Generating Query Results  <a name="generating-query-results"></a>

### Obullo support CodeIgniter Database Functions

------

#### result()

This function returns the query result as object.

#### resultArray();

This function returns the query result as a pure array, or an empty array when no result is produced.

#### rowArray();

Identical to the above row() function, except it returns an array.

#### numRows();

Return to number of rows.

```php
$query = $this->db->query("YOUR QUERY");

if ($query->numRows() > 0)
{
   $row = $query->rowArray();

   echo $row['title'];
   echo $row['name'];
   echo $row['body'];
} 
```

In addition, you can walk forward/backwards/first/last through your results using these variations:

<strong>
$row = $query->firstRow()
$row = $query->lastRow()
$row = $query->nextRow()
$row = $query->previousRow()
</strong>

By default they return an object unless you put the word "array" in the parameter:
<strong>
$row = $query->firstRow(assoc)
$row = $query->lastRow(assoc)
$row = $query->nextRow(assoc)
$row = $query->previousRow(assoc)
</strong>

### Standart Query Result Functions

-------

There are several ways to generate query results:

#### assoc()

This function **fetch one item** and returns query result as an associative array or **an empty array** on failure.

```php
$query = $this->db->query("YOUR QUERY");

$result = $query->assoc();
```

**Tip:** You can use <samp>$this</samp> instead of assigning a **$query** variable ...

```php
$this->db->query("YOUR QUERY");

$result = $this->db->assoc();
```

#### obj()

This function **fetch one item** and returns query result as object or **null** on failure.

```php
$query = $this->db->query("YOUR QUERY");

$result = $query->obj();
```

#### row()

Just alias of **obj()**
$query = $this->db->query("YOUR QUERY");

$result = $query->row();

#### both()

Get column names and numbers.

```php
$query = $this->db->query("SELECT article_id FROM table");

$result = $query->both(); 
// output  Array ( [article_id] => 1 [0] => 1 )  
```

#### rowCount()

Returns the number of rows affected by the execution of the last INSERT, DELETE, or UPDATE statement.

The most popular PDO database drivers like **MySQL** support to **rowCount();** function for SELECT statement but some database drivers does not support rowCount() function like **SQLite**.If you develop a portable applications **do not use** rowCount(); function via **SELECT** statements.

```php
$query = $this->db->query("INSERT UPDATE DELETE QUERY");

$result = $query->rowCount(); 
```

About number of rows ..

```php
// rowCount() Function support just

$query = $this->db->query("INSERT INTO articles 
(title, article) VALUES('test..','blabla..')");

echo $this->db->rowCount();  // output 1
```

Active record already return to affected rows not necassary to use rowCount();

```php
$data['title']   = 'row count test';
$data['article'] = 'blablabla ...';

$affected_rows = $this->db->insert('articles', $data);
echo $affected_rows;  // output 1
```

If your Pdo driver **does not** support **rowCount()**, to finding number of rows for the **SELECT** statement you can use native sql COUNT(*)

```php
echo $this->db->select("COUNT(*) as num")
->get('articles')->row()->num; // output 7

// or 

$query = $this->db->query("SELECT COUNT(*) as num FROM articles");
echo $query->row()->num; // output 7
```

An alternative way If data is not large and you already use fetchAll then you can use php count(); function

```php
$query = $this->db->query("SELECT * FROM articles");
$a = $query->fetchAll(assoc);

echo count($a);   // output 7
```

### Testing for Results

------
If you run queries that might not produce a result, you are encouraged to test for a result first using the **row()** and **prepare** function:

```php
$query = $this->db->prep()  // pdo prepare() switch 
->where('ip_address', '127.0.0.1')
->get('ob_sessions')    // from this table
->exec();

if($query->row())
{
    $query = $query->exec();  // get cached query..
    $b = $query->fetchAll(assoc);

    print_r($b);    // output array( ... )   
}
```

If **rowCount()** function available in your db driver you can use it ..

```php
$query = $this->db->where('ip_address', '127.0.0.1')
->get('ob_sessions');

if($query->rowCount() > 0)
{
    $b = $query->fetchAll(assoc);

    print_r($b);    // output array( ... )   
}
```

### Flexible Query Results

------

Just two function **fetch** and **fetchAll**, you should use them instead of standart query result functions (assoc(), row(), obj() and both()). Using to fetch and fetchAll functions will help you for the some **standardisation**.

#### fetch(int $fetch_style, int $cursor_orientation = ' ', int $cursor_offset = ' ')

Use this function to fetch **one item**.

```php
$query = $this->db->query(" ... ");

$result = $query->fetch(assoc);

 // output array( .. ) 
```

By default all fetch functions return an object unless you put the word **assoc** in the parameter

```php
$query = $this->db->query(" ... ");

$result = $query->fetch();

 // output object( .. ) 
```

#### fetchAll(int $fetch_style, int column index = 0, array ctor_args = array())


Use this function to fetch **all items**.

```php
$query = $this->db->query(" ... ");

$result = $query->fetchAll(assoc);

// output array( [0]=> array( 'field' => '', field2=> '' ), [1] => array(), ..)  
```

You can change the result type like this

```php
$query = $this->db->query(" ... ");

$result = $query->fetchAll();    // default object 

// output Array ( [0] => stdClass Object ( 'field' => '', ..) , [1] => stdClass Object ( )  ... )   
```

#### Result Types (Result Constants)

------
    
Below the table show available result types.

<table>
    <thead>
        <tr>
            <th>Function</th>    
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>ASSOC</td>
            <td>Fetch query result as an associative array</td>
        </tr>
        <tr>
            <td>OBJ</td>
            <td>Fetch query result as object</td>
        </tr>
        <tr>
            <td>LAZY</td>
            <td>Fetch each row as an object with variable names that correspond to the column names.</td>
        </tr>
        <tr>
            <td>NAMED</td>
            <td>Fetch each row as an array indexed by column name</td>
        </tr>
        <tr>
            <td>NUM</td>
            <td>Fetch each row as an array indexed by column number</td>
        </tr>
        <tr>
            <td>BOTH</td>
            <td>Fetch each row as an array indexed by both column name and numbers</td>
        </tr>
        <tr>
            <td>BOUND</td>
            <td>Specifies that the fetch method shall return TRUE and assign the values of the columns in the result set to the PHP variables to which they were bound with the PDO bindParam() or PDO bindColumn() methods.</td>
        </tr>
        <tr>
            <td>COLUMN</td>
            <td>Specifies that the fetch method shall return only a single requested column from the next row in the result set</td>
        </tr>
        <tr>
            <td>AS_CLASS</td>
            <td>Specifies that the fetch method shall return a new instance of the requested class, mapping the columns to named properties in the class.</td>
        </tr>
        <tr>
            <td>INTO</td>
            <td>Specifies that the fetch method shall update an existing instance of the requested class, mapping the columns to named properties in the class.</td>
        </tr>
        <tr>
            <td>KEY_PAIR</td>
            <td>Fetch into an array where the 1st column is a key and all subsequent columns are value. Note: Available since PHP 5.2.3</td>
        </tr>
        <tr>
            <td>CLASS_TYPE</td>
            <td>Determine the class name from the value of first column.</td>
        </tr>
        <tr>
            <td>SERIALIZE</td>
            <td>As into constant but object is provided as a serialized string.</td>
        </tr>
        <tr>
            <td>PROPS_LATE</td>
            <td>Note: Available since PHP 5.2.3</td>
        </tr>
        <tr>
            <td>FUNC</td>
            <td></td>
        </tr>
        <tr>
            <td>GROUP</td>
            <td></td>
        </tr>
        <tr>
            <td>UNIQUE</td>
            <td></td>
        </tr>
        <tr>
            <td>ORI_NEXT</td>
            <td>Fetch the next row in the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_PRIOR</td>
            <td>Fetch the previous row in the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_FIRST</td>
            <td>Fetch the first row in the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_LAST</td>
            <td>Fetch the last row in the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_ABS</td>
            <td>Fetch the requested row by row number from the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_REL</td>
            <td>Fetch the requested row by relative position from the current position of the cursor in the result set.</td>
        </tr>
    </tbody>
</table>

You can learn more details about [PDO Predefined Constants](http://php.net/manual/en/pdo.constants.php).

### Fetching Column Names

------

This is just a simple example, you don't need to extra effort for fetching column names. $query = $this->db->query("SELECT * FROM articles");

```php
$a = $query->fetch(assoc);

print_r(array_keys($a)); 

// Array ( [0] => article_id [1] => title [2] => article [3] => link [4] => creation_date )
```

#### fetchColumn(int 'col number')

Fecth column is a good pdo function, below the example shows an usage of fetchColumn function.

For example you have a table like this and you want to fetch value of one column..

```php
// column numbers
Col no: 0           1           2           3           4
 _ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __
|                                                                |
| article_id  |   title  |  article     |  link  | creation_date |
 __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ _
|             |          |              |        |               |
|     1       |  hello   |  blabla      |        | 2009-02-10    |
|             |          |              |        |               |
|     2       |  bonjour |  hello world |        | 2009-03-10    |
|             |          |              |        |               |
|     3       |  selam   |  selam dunya |        | 2009-04-10    |
| __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ |
```

I want to get values of column number 1 so code will be like this ..

```php
$query = $this->db->query("SELECT * FROM articles");

echo $query->fetchColumn(1).'<br />';
echo $query->fetchColumn(1).'<br />';
echo $query->fetchColumn(1).'<br />';
```

Now you want to fetch values of column number **1** and **2** , to getting multiple values we should use **PDO query caching** functionality like this ..

```php
$this->db->prep();   // tell to db class use pdo prepare
$this->db->query("SELECT * FROM articles");

$query = $this->db->exec();

echo $query->fetchColumn(1).'<br />';  // hello
echo $query->fetchColumn(1).'<br />';  // bonjour
echo $query->fetchColumn(1).'<br />';  // selam

echo '<br />';

$query = $this->db->exec();  // run cached query SELECT * FROM articles ..

echo $query->fetchColumn(2).'<br />';  // blabla
echo $query->fetchColumn(2).'<br />';  // hello world
echo $query->fetchColumn(2).'<br />';  // selam dunya
```

Now we have multiple column values and we build it very fast ..

**Note:** This pdo function especially designed for fetching a single column in the next row of a result set. 

## Query Helper Functions<a name="query-helper-functions"></a>

### Query Helper Functions

------

#### $this->db->insertId()

The insert ID number when performing database inserts.

#### $this->db->drivers()

Outputs the database platform you are running (MySQL, MS SQL, Postgres, etc...):

```php
$drivers = $this->db->drivers();   print_r($drivers);  // Array ( [0] => mssql [1] => mysql [2] => sqlite2 )
```
 
#### $this->db->version()

Outputs the database version you are running (MySQL, MS SQL, Postgres, etc...):

```php
echo $this->db->version(); // output like 5.0.45 or returns to null if server does not support this feature..
```

#### $this->db->isConnected()

Check the database connection is active or not

```php
echo $this->db->isConnected(); // output 1 or 0
```

#### $this->db->lastQuery();

Returns the last query that was run (the query string, not the result). Example:

```php
$str = $this->db->lastQuery();
```

#### $this->db->lastQuery(true);

Returns the <b>prepared</b> last query that was run (the query string, not the result). Example:

```php
$str = $this->db->lastQuery(TRUE);
```

#### $this->db->setAttribute($key, $val);

Set PDO connection attribute.

```php
$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

$this->db->query(" .. ");

print_r($this->db->errors());  // handling pdo errors

$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // restore error mode
```

#### $this->db->errors();

Get the database errors in pdo slient mode instead of getting pdo exceptions.

The following two functions help simplify the process of writing database INSERTs and UPDATEs.

#### $this->db->insertString();

This function simplifies the process of writing database inserts. It returns a correctly formatted SQL insert string. Example:

```php
$data = array('name' => $name, 'email' => $email, 'url' => $url);

$str = $this->db->insertString('table_name', $data);
```

The first parameter is the table name, the second is an associative array with the data to be inserted. The above example produces:

```php
INSERT INTO table_name (name, email, url) VALUES ('Ersin', 'ersin@example.com', 'example.com')
```

**Note:** Values are automatically escaped, producing safer queries.

#### $this->db->updateString();

This function simplifies the process of writing database updates. It returns a correctly formatted SQL update string. Example:

```php
$data = array('name' => $name, 'email' => $email, 'url' => $url);

$where = "author_id = 1 AND status = 'active'";

$str = $this->db->updateString('table_name', $data, $where);
```

The first parameter is the table name, the second is an associative array with the data to be updated, and the third parameter is the "where" clause. The above example produces:

```php
UPDATE table_name 
SET name = 'Ersin', 
email = 'ersin@example.com', 
url = 'example.com' 
WHERE author_id = 1 
AND status = 'active'
```

**Note:** Values are automatically escaped, producing safer queries.

## CRUD ( Active Record ) Class <a name="crud-class"></a>

### CRUD ( Active Record ) Class

------

Obullo uses a modified version of the CodeIgniter <b>Active Record Class</b>. This class allows information to be retrieved, inserted, and updated in your database with minimal scripting. In some cases only one or two lines of code are necessary to perform a database action.

Beyond simplicity, a major benefit to using the Active Record features is that it allows you to create database independent applications, since the query syntax is generated by each database adapter. It also allows for safer queries, since the values are escaped automatically by the system.

**Note:** If you intend to write your own queries you can disable this class in your <b>database config</b> file, allowing the core database library and adapter to utilize fewer resources.

**Tip**:You can also use <b>PDO query bind</b> functionality with Active Record Class. Look at <b>Next Page</b>.

    [Selecting Data](#selecting-data)
    [Inserting Data](#inserting-data)
    [Updating Data](#updating-data)
    [Deleting Data](#deleting-data)
    [Method Chaining](#method-chaining)
    [Active Record Caching](#active-record-chaining)

### Selecting Data<a name="#selecting-data"></a>

------

The following functions allow you to build SQL <b>SELECT</b> statements.

<b>Note: You can use method chaining for more compact syntax. This is described at the end of the page.</b>

#### $this->db->get();

Runs the selection query and returns the result. Can be used by itself to retrieve all records from a table:

```php
$query = $this->db->get('mytable');

// Produces: SELECT * FROM mytable
```

The second and third parameters enable you to set a limit and offset clause:

```php
$query = $this->db->get('mytable', 10, 20);

// Produces: SELECT * FROM mytable LIMIT 20, 10 (in MySQL. Other databases have slightly different syntax)
```
You will notice that the above function is assigned to a variable named <samp>$query</samp>, which can be used to show the results:

```php
$query = $this->db->get('mytable');

foreach($query->fetchAll() as $row)
{
    echo $row->title;
}
```

Please visit the [result functions](/docs/database/#generating-query-results) page for a full discussion regarding result generation.

#### $this->db->getWhere();

Identical to the above function except that it permits you to add a "where" clause in the second parameter, instead of using the db->where() function:

```php
$query = $this->db->getWhere('mytable', array('id' => $id), $limit, $offset);
```

#### $this->db->select();

Permits you to write the SELECT portion of your query:

```php
$this->db->select('title, content, date');

$query = $this->db->get('mytable');

// Produces: SELECT title, content, date FROM mytab
```

**Note:** If you are selecting all (*) from a table you do not need to use this function. When omitted, Obullo assumes you wish to SELECT *

$this->db->select() accepts an optional second parameter. If you set it to FALSE, Obullo will not try to protect your field or table names with backticks. This is useful if you need a compound select statement.

```php
$this->db->select("SELECT SUM(payments.amount), 
                   FROM_UNIXTIME( `field_date` , '%d.%m.%Y %H:%i' ) as date", FALSE);
                   
$query = $this->db->get('mytable');

// Produces:
SELECT SUM(payments.amount), 
FROM_UNIXTIME( `field_date` , '%d.%m.%Y %H:%i' ) as date
FROM mytable;
```

You can put " select commands " into $this->db->select() function like this ..

```php
$this->db->select("SELECT MAX(age) as max_age, active,
                   AVG(age) as avg_age,
                   DATE_FORMAT(field, '%d-%m-%Y') as date", FALSE);
                   
$this->db->where('active', 1);
$this->db->get('mytable');

// Produces:
SELECT MAX(age) as max_age, active,
AVG(age) as avg_age,
DATE_FORMAT(field, '%d-%m-%Y') as date
FROM mytable
WHERE active = '1'
```

#### $this->db->from();

Permits you to write the FROM portion of your query:

```php
$this->db->select('title, content, date');
$this->db->from('mytable');

$query = $this->db->get();

// Produces: SELECT title, content, date FROM mytable
```

**Note:** As shown earlier, the FROM portion of your query can be specified in the <dfn>$this->db->get()</dfn> function, so use whichever method you prefer.

#### $this->db->join();

Permits you to write the JOIN portion of your query:

```php
$this->db->select('*');
$this->db->from('blogs');
$this->db->join('comments', 'comments.id = blogs.id');

$query = $this->db->get();

// Produces:
```
Multiple function calls can be made if you need several joins in one query.

If you need something other than a natural JOIN you can specify it via the third parameter of the function. Options are: left, right, outer, inner, left outer, and right outer.

```php
$this->db->join('comments', 'comments.id = blogs.id', 'left');

// Produces: LEFT JOIN comments ON comments.id = blogs.id
```

#### $this->db->where();

This function enables you to set <b>WHERE</b> clauses using one of four methods:

**Note:** All values passed to this function are escaped automatically, producing safer queries except the LIKE statement, for like statements you should use $this->escapeLike() function for more details look at this page [running and escaping queries](/docs/database/running-and-escaping-queries).

    <ol><li><h4>Simple key/value method:</h4></li> 

```php
$this->db->where('name', $name);

// Produces: WHERE name = 'Joe'
```

Notice that the equal sign is added for you.

If you use multiple function calls they will be chained together with <var>AND</var> between them:

```php
    $this->db->where('name', $name);
    $this->db->where('title', $title);
    $this->db->where('status', $status);

    // WHERE name 'Joe' AND title = 'boss' AND status = 'active'
```
    
<li><h4>Custom key/value method:</h4></li>

    You can include an operator in the first parameter in order to control the comparison:

```php
    $this->db->where('name !=', $name);
    $this->db->where('id <', $id);

    // Produces: WHERE name != 'Joe' AND id < 45 
```    

<li><h4>Associative array method:</h4></li> 
    
```php    
$array = array('name' => $name, 'title' => $title, 'status' => $status);

$this->db->where($array);

// Produces: WHERE name = 'Joe' AND title = 'boss' AND status = 'active'
```

You can include your own operators using this method as well:

```php
$array = array('name !=' => $name, 'id <' => $id, 'date >' => $date);

$this->db->where($array);
```

<li><h4>Custom string:</h4></li>

You can write your own clauses manually:

```php
$where = "name='Joe' AND status='boss' OR status='active'";

$this->db->where($where);
```

$this->db->where() accepts an optional third parameter. If you set it to FALSE, Obullo will not try to protect your field or table names with backticks.

```php
$this->db->where('MATCH (field) AGAINST ("value")', NULL, FALSE);

```</ol>

#### $this->db->orWhere();

This function is identical to the one above, except that multiple instances are joined by OR:

```php
$this->db->where('name !=', $name);
$this->db->orWhere('id >', $id);

// Produces: WHERE name != 'Joe' OR id > 50
```

**Note:** or_where() was formerly known as orwhere(), which has been deprecated.

#### $this->db->whereIn();

**Note:** orWhere() was formerly known as orwhere(), which has been deprecated.

#### $this->db->whereIn();

Generates a WHERE field IN ('item', 'item') SQL query joined with AND if appropriate

```php
$names = array('Hassan', 'Bob', 'Yokamoto');
$this->db->whereIn('username', $names);

// Produces: WHERE username IN ('Hassan', 'Bob', 'Yokamoto')
```

#### $this->db->orWhereIn();

Generates a WHERE field IN ('item', 'item') SQL query joined with OR if appropriate

```php
$names = array('Frank', 'Todd', 'James');
$this->db->whereIn('username', $names);

// Produces: WHERE username IN ('Frank', 'Todd', 'James')
```

#### $this->db->whereNotIn();

Generates a WHERE field NOT IN ('item', 'item') SQL query joined with AND if appropriate

```php
$names = array('Frank', 'Todd', 'James');
$this->db->whereNotIn('username', $names);

// Produces: WHERE username NOT IN ('Frank', 'Todd', 'James')

```

#### $this->db->orWhereNotIn();

Generates a WHERE field NOT IN ('item', 'item') SQL query joined with OR if appropriate

```php
$names = array('Frank', 'Todd', 'James');
$this->db->orWhereNotIn('username', $names);

// Produces: OR username NOT IN ('Frank', 'Todd', 'James')
```

#### $this->db->like();

This function enables you to generate <b>LIKE</b> clauses, useful for doing searches.


**Note:** All values passed to this function are escaped automatically but if you use query bind functionality you must be use *$this->db->escape_like()* function manually, look at next page.

<ol>    
<li><h4>Simple key/value method:</h4></li>

```
 $this->db->like('title', 'match');

    // Produces: WHERE title LIKE '%match%' 
```

    If you use multiple function calls they will be chained together with <var>AND</var> between them:
    
```php
$this->db->like('title', 'match');
$this->db->like('body', 'match');

// WHERE title LIKE '%match%' AND body LIKE '%match% 
```

If you want to control where the wildcard (%) is placed, you can use an optional third argument. Your options are 'before', 'after' and 'both' (which is the default). 

```php
$this->db->like('title', 'match', 'before');
    // Produces: WHERE title LIKE '%match'

 $this->db->like('title', 'match', 'after');
    // Produces: WHERE title LIKE 'match%'

 $this->db->like('title', 'match', 'both');
    // Produces: WHERE title LIKE '%match%'
```   
 
<li><h4>Associative array method:</h4></li> 

```php
$array = array('title' => $match, 'page1' => $match, 'page2' => $match);

$this->db->like($array);

// WHERE title LIKE '%match%' AND page1 LIKE '%match%' AND page2 LIKE '%match%'
```</ol>


#### $this->db->orLike();

This function is identical to the one above, except that multiple instances are joined by OR:

```php
$this->db->like('title', 'match');
$this->db->orLike('body', $match);

// WHERE title LIKE '%match%' OR body LIKE '%match%'

```

#### $this->db->notLike();

This function is identical to <b>like()</b>, except that it generates NOT LIKE statements:

```php
$this->db->notLike('title', 'match');

// WHERE title NOT LIKE '%match%
```

#### $this->db->orNotLike();

This function is identical to <b>not_like()</b>, except that multiple instances are joined by OR:

```php
$this->db->notLike('title', 'match');
$this->db->orNotLike('body', 'match');

// WHERE title NOT LIKE '%match%
```

#### $this->db->groupBy();

Permits you to write the GROUP BY portion of your query:

```php
$this->db->groupBy("title");

// Produces: GROUP BY title
```

You can also pass an array of multiple values as well:


```php
$this->db->groupBy(array("title", "date"));

// Produces: GROUP BY title, date
```

#### $this->db->distinct();

Adds the <b>DISTINCT</b> keyword to a query

```php
$this->db->distinct();
$this->db->get('table');

// Produces: SELECT DISTINCT * FROM table
```

#### $this->db->having();

Permits you to write the HAVING portion of your query. There are 2 possible syntaxe, 1 argument or 2:

```php
$this->db->having('user_id = 45');
// Produces: HAVING user_id = 45

$this->db->having('user_id', 45);
// Produces: HAVING user_id = 45
```

You can also pass an array of multiple values as well:

```php
$this->db->having(array('title =' => 'My Title', 'id <' => $id));

// Produces: HAVING title = 'My Title', id < 45
```

If you are using a database that Obullo escapes queries for, you can prevent escaping content by passing an optional third argument, and setting it to FALSE.

```php
$this->db->having('user_id', 45);
// Produces: HAVING `user_id` = 45 in some databases such as MySQL

$this->db->having('user_id', 45, FALSE);
// Produces: HAVING user_id = 45

```

#### $this->db->orHaving();

Identical to having(), only separates multiple clauses with <b>OR</b>

#### $this->db->orderBy();

Lets you set an ORDER BY clause. The first parameter contains the name of the column you would like to order by. The second parameter lets you set the direction of the result. Options are <kbd>asc</kbd> or <kbd>desc</kbd>

Lets you set an ORDER BY clause. The first parameter contains the name of the column you would like to order by. The second parameter lets you set the direction of the result. Options are asc or desc

```php
$this->db->orderBy("title", "desc");

// Produces: ORDER BY title DESC 
```

You can also pass your own string in the first parameter:


```php
$this->db->orderBy('title desc, name asc');

// Produces: ORDER BY title DESC, name ASC
```

Or multiple function calls can be made if you need multiple fields.

```php
$this->db->orderBy("title", "desc");
$this->db->orderBy("name", "asc");


// Produces: ORDER BY title DESC, name ASC
```

#### $this->db->limit();

Lets you limit the number of rows you would like returned by the query:

```php
$this->db->limit(10);

// Produces: LIMIT 10
```

The second parameter lets you set a result offset.

```php
$this->db->limit(10, 20);

// Produces: LIMIT 20, 10 // in MySQL. Other databases have slightly different syntax
```

### Inserting Data

------

#### $this->db->insert();

Generates an insert string based on the data you supply, and runs the query. You can either pass an <b>array</b> or an <b>object</b> to the function. Here is an example using an array:

```php
$data = array(
               'title' => 'My title' ,
               'name' => 'My Name' ,
               'date' => 'My date'
            );

$this->db->insert('mytable', $data);

// Produces: INSERT INTO mytable (title, name, date) VALUES ('My title', 'My name', 'My date')
```
The first parameter will contain the table name, the second is an associative array of values.

Here is an example using an object:

```php
/*
    class Myclass {
        public $title = 'My Title';
        public $content = 'My Content';
        public $date = 'My Date';
    }
*/

$object = new Myclass;

$this->db->insert('mytable', $object);

// Produces: INSERT INTO mytable (title, content, date) VALUES ('My Title', 'My Content', 'My Date')
```

The first parameter will contain the table name, the second is an associative array of values.

**Note:** All values are escaped automatically producing safer queries.

#### $this->db->set();

This function enables you to set values for <dfn>inserts</dfn> or <dfn>updates</dfn>.

<b>It can be used instead of passing a data array directly to the insert or update functions:</b>

```php
$this->db->set('name', $name);
$this->db->insert('mytable');

// Produces: INSERT INTO mytable (name) VALUES ('{$name}')
```

If you use multiple function called they will be assembled properly based on whether you are doing an insert or an update:

```php
$this->db->set('name', $name);
$this->db->set('title', $title);
$this->db->set('status', $status);
$this->db->insert('mytable');
```

<b>set()</b> will also accept an optional third parameter ($escape), that will prevent data from being escaped if set to FALSE. To illustrate the difference, here is set() used both with and without the escape parameter.

```php
$this->db->set('field', 'field+1', FALSE);
$this->db->insert('mytable');
// gives INSERT INTO mytable (field) VALUES (field+1)

$this->db->set('field', 'field+1');
$this->db->insert('mytable');
// gives INSERT INTO mytable (field) VALUES ('field+1')
```

You can also pass an associative array to this function:

```php
$array = array('name' => $name, 'title' => $title, 'status' => $status);

$this->db->set($array);
$this->db->insert('mytable'); 
```

Or an object:

```php
/*
    class Myclass {
        public $title = 'My Title';
        public $content = 'My Content';
        public $date = 'My Date';
    }
*/

$object = new Myclass;

$this->db->set($object);
$affected_rows = $this->db->insert('mytable'); 
echo $affected_rows;  // 1
```

**Note:** INSERT , UPDATE and DELETE operations returns to affected rows automatically. You don't need to *rowCount()* function for these methods.

### Updating Data <a name="updating-data"></a>

------

#### $this->db->update();

Generates an update string and runs the query based on the data you supply. You can pass an <b>array</b> or an <b>object</b> to the function. Here is an example using an array:

```php
$data = array(
               'title' => $title,
               'name' => $name,
               'date' => $date
            );

$this->db->where('id', $id);
$affected_rows = $this->db->update('mytable', $data);
echo $affected_rows;  // 1
```

Or you can supply an object:

```php
/*
    class Myclass {
        var $title = 'My Title';
        var $content = 'My Content';
        var $date = 'My Date';
    }
*/

$object = new Myclass;

$this->db->where('id', $id);
$this->db->update('mytable', $object);
```

**Note:** All values are escaped automatically producing safer queries.

You'll notice the use of the $this->db->where() function, enabling you to set the WHERE clause. You can optionally pass this information directly into the update function as a string:

```php
$this->db->update('mytable', $data, "id = 4");
```
Or as an array:

```php
$this->db->update('mytable', $data, array('id' => $id));
```

You may also use the <dfn>$this->db->set()</dfn> function described above when performing updates.

### Deleting Data <a name="deleting-data"></a>

------

#### $this->db->delete();

Generates a delete SQL string and runs the query.

```php
$this->db->delete('mytable', array('id' => $id));
```
The first parameter is the table name, the second is the where clause. You can also use the <dfn>where()</dfn> or <dfn>or_where()</dfn> functions instead of passing the data to the second parameter of the function:

```php
$this->db->where('id', $id);
$affected_rows = $this->db->delete('mytable');
echo $affected_rows;  // 1
```

An array of table names can be passed into delete() if you would like to delete data from more than 1 table.

```php
$tables = array('table1', 'table2', 'table3');
$this->db->where('id', '5');
$this->db->delete($tables);
```

### Method Chaining<a name="method-chaining"></a>

------

Method chaining allows you to simplify your syntax by connecting multiple functions. Consider this example:

```php
$this->db
->select('title')
->from('mytable')
->where('id', $id)
->limit(10, 20)
->get();
```

You can also use query binding ..

```php
$query = $this->db->prep()  // tell to db class use pdo prepare
->select("DATE_FORMAT(creation_date, '%d-%m-%Y') as date, title",FALSE)
->where('title', ':title')
->where('title', ':title2')
->get('articles')           // get Function will be passive when u use prep()
->bindValue(':title', 'my title', param_str) 
->bindValue(':title2', 'my title', param_str)
->exec();

$a = $query->fetchAll(assoc);

print_r($a); // Array ( [0] => Array ( [date] => 00-00-0000 [title] => my title ) ) 

echo $this->db->lastQuery(TRUE);

// Query output:
```

### Active Record Caching<a name="active-record-chaining"></a>

------

While not <b>true</b> caching, Active Record enables you to save (or <b>cache</b>) certain parts of your queries for reuse at a later point in your script's execution. Normally, when an Active Record call is completed, all stored information is reset for the next call. With caching, you can prevent this reset, and reuse information easily.

Cached calls are cumulative. If you make 2 cached select() calls, and then 2 uncached select() calls, this will result in 4 select() calls. There are three Caching functions available:


#### $this->db->startCache()

This function must be called to begin caching. All Active Record queries of the correct type (see below for supported queries) are stored for later use.

#### $this->db->stopCache()

This function can be called to stop caching.

#### $this->db->flushCache()

This function deletes all items from the Active Record cache.

Here's a usage example:

```php
$this->db->startCache();
$this->db->select('field1');
$this->db->stopCache();

$this->db->get('tablename');

//Generates: SELECT `field1` FROM (`tablename`)

$this->db->select('field2');
$this->db->get('tablename');

//Generates: SELECT `field1`, `field2` FROM (`tablename`)

$this->db->flushCache();

$this->db->select('field2');
$this->db->get('tablename');

//Generates: SELECT `field2` FROM (`tablename`)
```

**Note:** The following statements can be cached: <samp>select, from, join, where, like, groupby, having, orderby, set</samp>
