## Quick Usage: PDO Examples <a name="quick-usage-pdo-examples"></a>

Why Obullo use PDO for database operations ? , download this [document](http://ilia.ws/files/quebec_PDO.pdf) it will give you some introductory information about PDO.

The following page contains example code showing how the database class is used. For complete details please read the individual pages describing each function.

### Initializing the Database Class <a name="initializing-the-database-class"></a>

------

The following code loads and initializes the database class based on your [configuration](/docs/database/database-configuration-and-connect) settings:

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

The [Active Record Pattern](/docs/database/active-record-class) gives you a simplified means of retrieving data:

```php
$query = $this->db->get('table_name');

foreach ($query->result() as $row)
{
    echo $row->title;
}
```

The above <dfn>get()</dfn> function retrieves all the results from the supplied table. The [Active Record](/docs/database/active-record-class) class contains a full compliment of functions for working with data.

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