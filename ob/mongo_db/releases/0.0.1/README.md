## Mongo Database Class


Mongo Class provides a lightweight and simple database management for popular NOSQL database type which is called <b>Mongodb</b>.

### Initializing the Class

------

```php
new Mongo_Db();
```


Once loaded, the database object will be available using: <dfn>$this->db->method();</dfn>

### Configuring Mongodb Options

------

You can set advanced mongodb options using app/config/mongodb.php file.

```php
<?php

/*
|--------------------------------------------------------------------------
| Mongo Db Config 
|--------------------------------------------------------------------------
| Mongodb database api configuration file.
| 
| Prototype: 
|
|   $mongo['key'] = value;
| 
*/

$mongo['host']         = 'localhost';
$mongo['port']         = '27017';
$mongo['database']     = '$yourdatabase';
$mongo['username']     = '$username';
$mongo['password']     = '$password';
$mongo['dsn']          = ''; // mongodb://connection_string

/*
|--------------------------------------------------------------------------
| Safe Queries
|--------------------------------------------------------------------------
| Writing speed and safety options.
| 
| Options:
|   
|   fysnc = Boolean, defaults to false. Forces the insert to be synced to disk before returning success.
|   wtimewout = How long to wait for WriteConcern acknowledgement.The default value for MongoClient is 10000 milliseconds.
|   timeout = If acknowledged writes are used, this sets how long (in milliseconds) for the client to wait for a database response.
|  
|   @link http://www.php.net/manual/en/mongocollection.save.php
| 
|  Write Concerns:
|   w=0         Unacknowledged	A write will not be followed up with a GLE call, and therefore not checked ("fire and forget")
|   w=1         Acknowledged	The write will be acknowledged by the server (the primary on replica set configuration)
|   w=N         Replica Set Acknowledged	The write will be acknowledged by the primary server, and replicated to N-1 secondaries.
|   w=majority	Majority Acknowledged	The write will be acknowledged by the majority of the replica set (including the primary). This is a special reserved string.
|   w=<tag set>	Replica Set Tag Set Acknowledged	The write will be acknowledged by members of the entire tag set
|   j=true	Journaled	The write will be acknowledged by primary and the journal flushed to disk
|
|   @link http://www.php.net/manual/en/mongo.writeconcerns.php
*/
$mongo['query_safety'] = array('w' => 0, 'j' => 1);

/*
|--------------------------------------------------------------------------
| Connection Flag
|--------------------------------------------------------------------------
| If you are having connection problems try change set to true.
|
| if set true db will available end of the the connection string.
| mongodb://[username:password@]/host/{dbname} 
|
*/
$mongo['dbname_flag'] = false;

/* End of file mongo.php */
/* Location: .app/config/mongo.php */
```

```php
new Mongo_Db();
$docs = $this->mongo->get('users');

if($docs->hasNext())
{
    foreach($docs as $row)
    {
        echo $row['username'].'<br />';
    }
}
```

### All Popular Crud Functions Available

```php
$this->mongo->select();
$this->mongo->where('username', 'bob');
$docs = $this->mongo->get('users');

if($docs ->hasNext())
{
   $row = (object)$docs->getNext();
   echo $row->username;
}
```

### Using Mongo_Db Class for General CRUD Operations.

------

Go to your <dfn>app/config/database.php</dfn> file and set the database driver as mongo.

```php
$database['db']['hostname']  = "localhost";
$database['db']['username']  = "root";
$database['db']['password']  = "12345";
$database['db']['database']  = "yourdbname";
$database['db']['driver']    = "mongo";
```

### CRUD ( Create, read, update and delete ) Functions

------

#### $this->db->insert($collection, $data, $options)

```php
$options = array('query_safety' => TRUE); // Optionally

$affected_rows = $this->db->insert('users', array('username'  => 'john', 'date' => new MongoDate()), $options);

echo $affected_rows.' row(s) added to database !'; '  // 2 row(s) added to database !';
```

#### $this->db->update($collection, $data)

```php
$options = array('query_safety' => TRUE);  // Optionally

$this->db->where('_id', new MongoId('50a39b5e1657ae3817000000'));
$this->db->update('users', array('username' => 'bob'), $options) 
```

```php
$this->db->where('username', 'john');
$this->db->update('users', array('username' => 'bob', 'ts' => new MongoDate()))
```

#### $this->db->delete($collection)

```php
$this->db->where('_id', new MongoId('50a39b5e1657ae3817000000'));
$this->db->delete('users')
```

#### $this->db->get()

```php
$this->db->get() and $this->db->find() functions returns to Mongo::Cursor Object.
$docs = $this->db->get('users'); // returns to Mongo::Cursor Object

echo 'found '.$docs->count().' row(s)';

foreach($docs as $row)
{
   echo $row['username'].'<br />';
}
```

Fetching one row as a object. $docs = $this->db->get('users');

```php
if($docs->hasNext())
{
     $row = (object) getNext();
}

echo $row->username;
```

```php
$this->db->select()
$this->db->select('username,  user_firstname');

$docs = $this->db->get('users');
$row = $docs->getNext();

echo $row['username'];

 // Also you can provide array.
$this->db->select(array('username', 'user_firstname'));
$docs = $this->db->get('users');
$row = $docs->getNext();
```


#### $this->db->from()

Especially before the find operations we use from method setting to collection name.

```php
$this->db->from('users');
$docs = $this->db->find(array('$or' => array(array('username' => 'john'), array('username' => 'bob'))));

foreach($docs as  $row)
{
    echo $row->username. '<br />';
}
```

#### $this->db->where()

```php
$this->db->where('username', 'bob');
$docs = $this->db->get('users');

if($docs ->hasNext())
{
   $row = $docs->getNext();
   echo $row['username'];
}
```


#### $this->db->where('field >', 'value') ( Greater than )

```php
$this->db->where('foo >', 20)->get('foobar');
```

#### $this->db->where('field <', 'value') ( Less than )

```php
$this->db->where('foo <', 20)->get('foobar');
```

#### $this->db->where('field >=', 'value') ( Greater than or equal to )

```php
$this->db->where('foo >=', 20)->get('foobar');
```

#### $this->db->where('field <=', 'value') ( Less than or equal to )

```php
$this->db->where('foo <=', 20)->get('foobar');
```

#### $this->db->where('field !=', 'value') ( Not equal to )

```php
$this->db->where('foo !=', 20)->get('foobar');
```

#### $this->db->or_where()

```php
$this->db->or_where('username', 'bob');
$this->db->or_where('username', 'john');

$docs = $this->db->get('users');
```

#### $this->db->where_in()

```php
$this->db->where_in('username', array('bob', 'john', 'jenny'));
```

#### $this->db->where_in() ( Not In )

```php
$this->db->where_in('username !=', array('bob', 'john', 'jenny'));

$docs = $this->db->get('users');
```

#### $this->db->where_in_all()

Get the documents where the value of a $field is in all of a given $in array().

```php
$docs = $this->db->where_in_all('foo', array('bar', 'zoo', 'blah'))->get('users');
```

#### $this->db->like($field = "", $value = "", $flags = "i", $enable_start_wildcard = TRUE, $enable_end_wildcard = TRUE)

```php
$this->db->like('username', 'bob');
```

##### Flags

```php
*  @param $flags
     *  Allows for the typical regular expression flags:
     *   i = case insensitive
     *   m = multiline
     *   x = can contain comments
     *   l = locale
     *   s = dotall, "." matches everything, including newlines
     *   u = match unicode
     *
     *  @param $enable_start_wildcard
     *  If set to anything other than TRUE, a starting line character "^" will be prepended
     *  to the search value, representing only searching for a value at the start of
     *  a new line.
     *
     *  @param $enable_end_wildcard
     *  If set to anything other than TRUE, an ending line character "$" will be appended
     *  to the search value, representing only searching for a value at the end of
     *  a line.
```

#### $this->db->or_like()

```php
$this->db->or_like('username', 'bob');
```

#### $this->db->not_like()

```php
$this->db->not_like('username', 'bob');
```

#### $this->db->order_by()

```php
$this->db->where_in('username', array('bob', 'john', 'jenny'));
$this->db->order_by('username', 'ASC');

$docs = $this->db->get('users');
```

#### $this->db->limit()

```php 
$this->db->where_in('username', array('bob', 'john', 'jenny'));
$this->db->order_by('username', 'ASC');
$this->db->limit(10);

$docs = $this->db->get('users');
```

#### $this->db->offset()

```php
$this->db->where_in('username', array('bob', 'john', 'jenny'));
$this->db->order_by('username', 'ASC');
$this->db->limit(10);
$this->db->offset(20);
$docs = $this->db->get('users');
```

#### $this->db->find($criteria, $fields)

```php
$this->db->from('users');
$docs = $this->db->find(array('$or' => array(array('username' => 'john'), array('username' => 'bob'))),  array('username'));

foreach($docs as  $row)
{
    echo $row->username. '<br />';
}
```

#### $this->db->find_one($criteria, $fields)

```php
$this->db->select('username);
$this->db->from('users');
$docs = $this->db->find(array('$or' => array(array('username' => 'john'), array('username' => 'bob'))));

foreach($docs as  $row)
{
    echo $row->username. '<br />';
}
```

#### $this->db->insertId()

```php
$this->db->insert(''users', array('username' => 'john28', 'ts' => new MongoDate()));

echo $this->db->insertId();   // last inserted Mongo ID.
```

#### $this->db->inc()

Increments the value of a field.

```php
$this->db->where(array('blog_id'=>123))->inc(array('num_comments' => 1))->update('blog_posts');
```

#### $this->db->dec()

Decrements the value of a field.

```php
$this->db->where(array('blog_id'=>123))->dec(array('num_comments' => 1))->update('blog_posts');
```

#### $this->db->set()

Sets a field to a value.

```php
$this->db->where(array('blog_id'=>123))->set('posted', 1)->update('blog_posts'); $this->db->where(array('blog_id'=>123))->set('posted', 1)->update('blog_posts');
```

#### $this->db->unset_field()

Unsets a field (or fields).

```php
$this->db->where(array('blog_id'=>123))->unset('posted')->update('blog_posts'); $this->db->where(array('blog_id'=>123))->set(array('posted','time'))->update('blog_posts');
```

#### $this->db->addtoset()

Adds value to the array only if its not in the array already.

```php
$this->db->where(array('blog_id'=>123))->addtoset('tags', 'php')->update('blog_posts'); 
$this->db->where(array('blog_id'=>123))->addtoset('tags', array('php', 'obullo', 'mongodb'))->update('blog_posts');
```

#### $this->db->pull()

Removes by an array by the value of a field.

```php
$this->db->pull('comments', array('comment_id'=>123))->update('blog_posts');
```

#### $this->db->push()

Pushes values into a field (field must be an array).

```php
$this->db->where(array('blog_id'=>123))->push('comments', array('text'=>'Hello world'))->update('blog_posts'); $this->db->where(array('blog_id'=>123))->push(array('comments' => array('text'=>'Hello world')), 'viewed_by' => array('Alex')->update('blog_posts');
```

#### $this->db->pop()

Pops the last value from a field (field must be an array).

```php
$this->db->where(array('blog_id'=>123))->pop('comments')->update('blog_posts'); 
$this->db->where(array('blog_id'=>123))->pop(array('comments', 'viewed_by'))->update('blog_posts');
```

#### $this->db->batchInsert()

Insert a multiple new document into the passed collection. Forexample you need to insert one billion record to database at once.

```php
$this->db->batchInsert('foo',  $data = array();
```

#### $this->db->getInstance()

Returns to Mongodb instance of object.

Store a gridfs file using mongo instance.

```php
$gridFS = $this->db->getInstance()->getGridFS();  // get a MongoGridFS instance
$id     = $gridFS->storeFile($filepath, array(
                                                  'filename' => uniqid(time()), 
                                                  'filetype' => $_FILES['field]['type'],
                                                  'filegroup'=> 'profile-picture',
                                                  'caption'  => 'Profile Picture'));
 echo $id;
```

Remove a gridfs file using mongo instance.

```php
$gridFS = $this->db->getInstance()->getGridFS();
$gridFS->remove(array('user_id' => new MongoId($this->auth->data('_id')), 'filegroup' => 'profile-picture'));
```