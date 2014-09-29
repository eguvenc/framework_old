## Database Transactions <a name="database-transactions"></a>

### Transactions

------

Database abstraction allows you to use transactions with databases that support transaction-safe table types. In MySQL, you'll need to be running <b>InnoDB</b> or <b>BDB</b> table types rather than the more common MyISAM. Most other database platforms support transactions natively.

If you are not familiar with transactions we recommend you find a good online resource to learn about them for your particular database. The information below assumes you have a basic understanding of transactions.

### Running Transactions

------

To run your queries using transactions you will use the <kbd>$this->db->transaction()</kbd>, <kbd>$this->db->commit()</kbd> and <kbd>$this->db->rollback()</kbd> methods as follows:

```php
try {
    
    $this->db->transaction(); // begin the transaction
    
    // INSERT statements
    
    $this->db->execQuery("INSERT INTO persons (person_type, person_name) 
    VALUES ('lazy', 'ersin')");
    
    $this->db->execQuery("INSERT INTO persons (person_type, person_name) 
    VALUES ('clever', 'john')");
    
    $this->db->execQuery("INSERT INTO persons (person_type, person_name) 
    VALUES ('funny', 'bob')");


    $this->db->commit();    // commit the transaction

    echo 'Data entered successfully<br />'; // echo a message to say the database was created

} catch(Exception $e)
{    
    $this->db->rollback();       // roll back the transaction if we fail
       
    echo $e->getMessage();  // echo exceptional error message
}
```

You can run as many queries as you want between the transaction/commit functions and they will all be committed or rolled back based on success or failure of any given query.

### Running Transactions with CRUD Class

------

Also you use active record class like this

```php
try {
    
    $this->db->transaction(); // begin the transaction
    
    // INSERT statements
        
    $this->db->insert('persons', 
    array('person_type' => 'lazy',
          'person_name' => 'ersin'));
          
    $this->db->insert('persons', 
    array('person_type' => 'clever',
          'person_name' => 'john'));
          
    $this->db->insert('persons', 
    array('person_type' => 'funny',
          'person_name' => 'bob'));

    $this->db->commit();    // commit the transaction

    echo 'Data entered successfully<br />'; // echo a message to say the database was created

} catch(Exception $e)
{    
    $this->db->rollback();       // roll back the transaction if we fail
       
    echo $e->getMessage();  // echo exceptional error message
}
```