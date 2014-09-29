## Running and Escaping Queries <a name="running-and-escaping-queries"></a>

### Direct Query

------

To submit a query, use the following function:

```php
$this->db->query('YOUR QUERY HERE');
```

The <dfn>query()</dfn> function returns a database result **object** when "read" type queries are run, which you can use to show your results. When retrieving data you will typically assign the query to your own variable, like this:

```php
$query = $this->db->query('YOUR QUERY HERE');
```

### Exec Query

------

This query type same as direct query just it returns the $affected_rows automatically. You should use **execQuery** function for INSERT, UPDATE, DELETE operations.

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

**Note:** You don't need to **$this->escapeLike** function when you use active record class because of active record(CRUD) class use auto escape foreach like condition.

```php
$query = $this->db->select("*")
->like('article','%%blabla')
->orLike('article', 'blabla')
->get('articles');

echo $this->db->getLastQuery();

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
$this->db->fetchAll(ASSOC);

echo $this->db->getLastQuery();

// Output
```

### Query Bindings

------

Framework offers PDO bindValue and bindParam functionality, using bind operations will help you for the performance and security:

#### Bind Types

<table>
    <thead>
        <tr>
            <th>Friendly Constant</th>
            <th>PDO CONSTANT</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>PARAM_BOOL</td>
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
$a = $this->db->fetch(ASSOC);

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
$a = $this->db->fetch(ASSOC);

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
$a = $query->fetch(ASSOC); 
print_r($a);
```

#### Automatically Bind Query

```php
$this->db->prep();  
$this->db->query("SELECT * FROM articles WHERE article_id=:id OR link=:code");

$values[':id']   = '1';
$values[':code'] = 'i see dead people';

$this->db->exec($values);

$a = $this->db->fetch(ASSOC);
print_r($a);
```
**Important:** Framework does not support Question Mark binding at this time.
