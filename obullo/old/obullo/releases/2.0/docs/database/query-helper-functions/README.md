## Query Helper Functions<a name="query-helper-functions"></a>

### Query Helper Functions

------

#### $this->db->insertId()

The insert ID number when performing database inserts.

#### $this->db->getDrivers()

Outputs the database platform you are running (MySQL, MS SQL, Postgres, etc...):

```php
$drivers = $this->db->getDrivers();   print_r($drivers);  // Array ( [0] => mssql [1] => mysql [2] => sqlite2 )
```
 
#### $this->db->getVersion()

Outputs the database version you are running (MySQL, MS SQL, Postgres, etc...):

```php
echo $this->db->version(); // output like 5.0.45 or returns to null if server does not support this feature..
```

#### $this->db->isConnected()

Checks the database connection is active or not

```php
echo $this->db->isConnected(); // output 1 or 0
```

#### $this->db->getLastQuery();

Returns the last query that was run (the query string, not the result). Example:

```php
$str = $this->db->lastQuery();
```

#### $this->db->getLastQuery(true);

Returns the <b>prepared</b> last query that was run (the query string, not the result). Example:

```php
$str = $this->db->lastQuery(true);
```

#### $this->db->setAttribute($key, $val);

Sets PDO connection attribute.

```php
$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

$this->db->query(" .. ");

print_r($this->db->errors());  // handling pdo errors

$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // restore error mode
```

#### $this->db->getErrors();

Gets the database errors in pdo slient mode instead of getting pdo exceptions.

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
