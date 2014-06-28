## Generating Query Results  <a name="generating-query-results"></a>

### Query Results

------

#### $this->db->getResult()

This function returns the query result as object.

#### $this->db->getResultArray();

This function returns the query result as a pure array, or an empty array when no result is produced.

#### $this->db->getRow();

This function fetches one item and returns query result as object or false on failure.

#### $this->db->getRowArray();

Identical to the above row() function, except it returns an array.

#### $this->db->getCount();

Returns the number of rows.

```php
$this->db->query('YOUR QUERY');

if ($this->db->getCount() > 0)
{
   $row = $this->db->getRowArray();

   echo $row['title'];
   echo $row['name'];
   echo $row['body'];
} 
```

### Testing for Results

------
If you run queries that might not produce a result, you are encouraged to test for a result first using the **row()** and **prepare** function:

```php
$this->db->prep()  // pdo prepare() switch 
->where('ip_address', '127.0.0.1')
->get('frm_sessions')    // from this table
->exec();

if($this->db->getRow())
{
    $this->db->exec();  // get cached query..
    $b = $this->db->getResultArray();

    print_r($b);    // output array( ... )   
}
```

If **getCount()** function available in your db driver you can use it ..

```php
$this->db->where('ip_address', '127.0.0.1')->get('frm_sessions');

if($this->db->getCount() > 0)
{
    $b = $this->db->getResultArray();

    print_r($b);    // output array( ... )   
}
```