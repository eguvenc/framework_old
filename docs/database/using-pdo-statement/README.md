## PDO Statement <a name="pdo-statement"></a>

### Query Bindings

------

Obullo offers PDO <b>bindValue</b> and <b>bindParam</b> functionalities, using to bind operations will help you for the some <b>performance</b> and <b>security</b>:

### Bind Types

------

<table><thead><tr>
<th>Obullo Friendly Constant</th><th>PDO CONSTANT</th><th>Description</th></tr></thead><tbody>
<tr><td>PARAM_BOOL</td><td>PDO::PARAM_BOOL</td><td>Boolean</td></tr>
<tr><td>PARAM_NULL</td><td>PDO::PARAM_NULL</td><td>NULL</td></tr>
<tr><td>PARAM_INT</td><td>PDO::PARAM_INT</td><td>Integer</td></tr>
<tr><td>PARAM_STR</td><td>PDO::PARAM_STR</td><td>String</td></tr>
<tr><td>PARAM_LOB</td><td>PDO::PARAM_LOB</td><td>Large Object Data (LOB)</td></tr></tbody></table>

### Bind Value Example

------

#### $this->db->bindValue($paramater, $value, $data_type)

```php
$this->db->prep();   // tell to db class use pdo prepare 
$this->db->query("SELECT * FROM articles WHERE article_id=:id OR link=:code");

$this->db->bindValue(':id', 1, param_int);  // Integer 
$this->db->bindValue(':code', 'i see dead people', param_str); // String      

$this->db->exec();  // execute query
$a = $this->db->rowArray();

print_r($a);
```

The <b>double dots</b> in the query are automatically replaced with the values of <b>bindValue</b> functions.

### Bind Param Example

------

#### $this->db->bindParam($paramater, $variable, $data_type, $data_length, $driver_options = array())

```php
$this->db->prep();   // tell to db class use pdo prepare 
$this->db->query("SELECT * FROM articles WHERE article_id=:id OR link=:code");

$this->db->bindParam(':id', 1, param_int, 11);   // Integer 
$this->db->bindParam(':code', 'i see dead people', param_str, 20); // String (int Length)      

$this->db->exec();  // execute query
$a = $this->db->rowArray();

print_r($a);
```

The <b>double dots</b> in the query are automatically replaced with the values of <b>bindParam</b> functions.

<b>The secondary benefit of using binds is that the values are automatically escaped, producing safer queries. You don't have to remember to manually escape data; the engine does it automatically for you.</b>

#### A Short Way ..

```php
$query = $this->db->prep()
 ->query("SELECT * FROM articles WHERE article_id=:id OR link=:code");

$query->bindValue(':id', 1, PARAM_INT);  
$query->bindValue(':code', 'i-see-dead-people', PARAM_STR); 

$query->exec();
$a = $query->rowArray(); 
print_r($a);
```

### Automatically Bind Query

------

```php
$values[':id']   = '1';
$values[':code'] = 'i see dead people';

$res= $this->db->prep()
->query("SELECT * FROM articles WHERE article_id=:id OR link=:code")
->exec($values)
->rowArray();

print_r($res);
```

**Important:** Obullo does not support Question Mark binding at this time.

### Query Binding with Active Record Class

------

Obullo allows to use query bind functionality with active record class like this ..

#### Using Auto Bind

```php
$title = 'some-title';

$query = $this->db->prep()   // tell to db class use pdo prepare
->select("*")
->where('title', ':title')
->get('articles')
->exec(array(':title' => $title));  // when you use prepare() at the top,
                                    // get() Function will switched to passive
                                    // so exec() is your active function .. 
                                    
$a = $query->resultArray();

print_r($a)
```

#### Using BindParam

```php
$this->db->prep();   // tell to db class use pdo prepare
$this->db
->select("*")
->where('title', ':title')
->where('active', ':active')
->get('articles');   

$this->db->bindParam(':title', 'some title', PARAM_STR, 20); // String (int Length)
$this->db->bindParam(':active', 1, PARAM_BOOL);          // Int (int Length)

$query = $this->db->exec();
$a = $query->resultArray();

print_r($a);
```
As you can see above the example <b>bindParam</b> is a very secure function, for example "some title" is a string and it must be in <b>20 characters</b> length

#### Using Secure Like Conditions

When using query bind functionality you must use <b>$this->db->escapeLike()</b> for secure queries because of pdo query bind does not allow to use escape like function automatically.

<b>both</b>

```php
$this->db->prep();
$this->db->select("*");
$this->db->like('article',":like");
$this->db->get('articles');

$bad_value = '%';

$this->db->exec(array(':like' => $this->db->escapeLike($bad_value)));

 // Produces: SELECT * FROM (`articles`) WHERE `article` LIKE '%\\%%' 
```

<b>before</b>

```php
$this->db->prep();
$this->db->select("*");
$this->db->like('article',":like");
$this->db->get('articles');

$value = 'some';

$this->db->exec(array(':like' => $this->db->escapeLike($value, 'before')));

 // Produces: SELECT * FROM (`articles`) WHERE `article` LIKE '%some' 
```

<b>after</b>

```php
$this->db->prep();
$this->db->select("*");
$this->db->like('article',":like");
$this->db->get('articles');

$value = 'some';

$this->db->exec(array(':like' => $this->db->escapeLike($value, 'after')));

 // Produces: SELECT * FROM (`articles`) WHERE `article` LIKE 'some%' 
```