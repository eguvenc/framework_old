## Mongo Database Class

------

Mongo Class provides a lightweight and simple database management for popular NOSQL database type which is called <b>Mongodb</b>.

### Database Configuration

------

Go to your <dfn>app/config/database.php</dfn> file and set the database.

```php
$database['db']['hostname']  = "localhost";
$database['db']['username']  = "root";
$database['db']['password']  = "12345";
$database['db']['database']  = "yourdbname";
$database['db']['dbdriver']  = "mongodb";
```

### Initializing the Class

------

new Db();

Once loaded, the database object will be available using: <dfn>$this->db->method();</dfn>
Configuring Mongodb Options

You can set advanced mongodb options using app/config/mongodb.php file.
<?php
$mongodb['host']         = 'localhost';
$mongodb['port']         = '27017';
$mongodb['database']     = '';
$mongodb['username']     = '';
$mongodb['password']     = '';
|--------------------------------------------------------------------------
| Persistent connections
|--------------------------------------------------------------------------
|
*/
$mongodb['persist']      = FALSE;
$mongodb['persist_key']  = 'ob_mongo_persist';
|--------------------------------------------------------------------------
| Safe Queries
|--------------------------------------------------------------------------
| Writing speed and safety options.
| 
| Options:
|
|   none  = Default, high speed.
|   safe  = The database has receieved and executed the query
|   fysnc = as above + the change has been committed to harddisk. 
|   ( NOTE: Will introduce a performance penalty ).
|
*/
$mongodb['query_safety'] = 'safe';
|--------------------------------------------------------------------------
| Connection Flag
|--------------------------------------------------------------------------
| If you are having connection problems try change set to TRUE.
|
*/
$mongodb['timeout']      = 100;
$mongodb['host_db_flag'] = FALSE;

//* End of file mongodb.php */
/* Location: .app/config/mongodb.php */
CRUD ( Create, read, update and delete ) Functions

$this->db->insert($collection, $data, $options)
$options = array('query_safety' => TRUE); // Optionally

$affected_rows = $this->db->insert('users', array('username'  => 'john', 'date' => new MongoDate()), $options);

echo $affected_rows.' row(s) added to database !'; '  // 2 row(s) added to database !';
$this->db->update($collection, $data)
$options = array('query_safety' => TRUE);  // Optionally

$this->db->where('_id', new MongoId('50a39b5e1657ae3817000000'));
$this->db->update('users', array('username' => 'bob'), $options) $this->db->where('username', 'john');
$this->db->update('users', array('username' => 'bob', 'ts' => new MongoDate()))
$this->db->delete($collection)
$this->db->where('_id', new MongoId('50a39b5e1657ae3817000000'));
$this->db->delete('users')
$this->db->get()

$this->db->get() and $this->db->find() functions returns to Mongo::Cursor Object.
$docs = $this->db->get('users'); // returns to Mongo::Cursor Object

echo 'found '.$docs->count().' row(s)';

foreach($docs as $row)
{
   echo $row['username'].'<br />';
}
Fetching one row as a object. $docs = $this->db->get('users');

if($docs->hasNext())
{
     $row = (object) getNext();
}

echo $row->username;
$this->db->select()
$this->db->select('username,  user_firstname');

$docs = $this->db->get('users');
$row = $docs->getNext();

echo $row['username'];

 // Also you can provide array.
$this->db->select(array('username', 'user_firstname'));
$docs = $this->db->get('users');
$row = $docs->getNext();
$this->db->from()

Especially before the find operations we use from method setting to collection name.
$this->db->from('users');
$docs = $this->db->find(array('$or' => array(array('username' => 'john'), array('username' => 'bob'))));

foreach($docs as  $row)
{
    echo $row->username. '<br />';
}
$this->db->where()
$this->db->where('username', 'bob');

$docs = $this->db->get('users');

if($docs ->hasNext())
{
   $row = $docs->getNext();
   echo $row['username'];
}
$this->db->where('field >', 'value') ( Greater than )
$this->db->where('foo >', 20)->get('foobar');
$this->db->where('field <', 'value') ( Less than )
$this->db->where('foo <', 20)->get('foobar');
$this->db->where('field >=', 'value') ( Greater than or equal to )
$this->db->where('foo >=', 20)->get('foobar');
$this->db->where('field <=', 'value') ( Less than or equal to )
$this->db->where('foo <=', 20)->get('foobar');
$this->db->where('field !=', 'value') ( Not equal to )
$this->db->where('foo !=', 20)->get('foobar');
$this->db->or_where()
$this->db->or_where('username', 'bob');
$this->db->or_where('username', 'john');

$docs = $this->db->get('users');
$this->db->where_in()
$this->db->where_in('username', array('bob', 'john', 'jenny'));
$this->db->where_in() ( Not In )
$this->db->where_in('username !=', array('bob', 'john', 'jenny'));

$docs = $this->db->get('users');
$this->db->where_in_all()

Get the documents where the value of a $field is in all of a given $in array().
$docs = $this->db->where_in_all('foo', array('bar', 'zoo', 'blah'))->get('users');
$this->db->like($field = "", $value = "", $flags = "i", $enable_start_wildcard = TRUE, $enable_end_wildcard = TRUE)
$this->db->like('username', 'bob');
Flags
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
$this->db->or_like()
$this->db->or_like('username', 'bob');
$this->db->not_like()
$this->db->not_like('username', 'bob');
$this->db->order_by()
$this->db->where_in('username', array('bob', 'john', 'jenny'));
$this->db->order_by('username', 'ASC');

$docs = $this->db->get('users');
$this->db->limit()
$this->db->where_in('username', array('bob', 'john', 'jenny'));
$this->db->order_by('username', 'ASC');
$this->db->limit(10);

$docs = $this->db->get('users');
$this->db->offset()
$this->db->where_in('username', array('bob', 'john', 'jenny'));
$this->db->order_by('username', 'ASC');
$this->db->limit(10);
$this->db->offset(20);
$docs = $this->db->get('users');
$this->db->find($criteria, $fields)
$this->db->from('users');
$docs = $this->db->find(array('$or' => array(array('username' => 'john'), array('username' => 'bob'))),  array('username'));

foreach($docs as  $row)
{
    echo $row->username. '<br />';
}
$this->db->find_one($criteria, $fields)
$this->db->select('username);
$this->db->from('users');
$docs = $this->db->find(array('$or' => array(array('username' => 'john'), array('username' => 'bob'))));

foreach($docs as  $row)
{
    echo $row->username. '<br />';
}
$this->db->insert_id()
$this->db->insert(''users', array('username' => 'john28', 'ts' => new MongoDate()));

echo $this->db->insert_id();   // last inserted Mongo ID.
$this->db->inc()

Increments the value of a field.
$this->db->where(array('blog_id'=>123))->inc(array('num_comments' => 1))->update('blog_posts');
$this->db->dec()

Decrements the value of a field.
$this->db->where(array('blog_id'=>123))->dec(array('num_comments' => 1))->update('blog_posts');
$this->db->set()

Sets a field to a value.
$this->db->where(array('blog_id'=>123))->set('posted', 1)->update('blog_posts'); $this->db->where(array('blog_id'=>123))->set('posted', 1)->update('blog_posts');
$this->db->unset_field()

Unsets a field (or fields).
$this->db->where(array('blog_id'=>123))->unset('posted')->update('blog_posts'); $this->db->where(array('blog_id'=>123))->set(array('posted','time'))->update('blog_posts');
$this->db->addtoset()

Adds value to the array only if its not in the array already.
$this->db->where(array('blog_id'=>123))->addtoset('tags', 'php')->update('blog_posts'); $this->db->where(array('blog_id'=>123))->addtoset('tags', array('php', 'obullo', 'mongodb'))->update('blog_posts');
$this->db->pull()

Removes by an array by the value of a field.
$this->db->pull('comments', array('comment_id'=>123))->update('blog_posts');
$this->db->push()

Pushes values into a field (field must be an array).
$this->db->where(array('blog_id'=>123))->push('comments', array('text'=>'Hello world'))->update('blog_posts'); $this->db->where(array('blog_id'=>123))->push(array('comments' => array('text'=>'Hello world')), 'viewed_by' => array('Alex')->update('blog_posts');
$this->db->pop()

Pops the last value from a field (field must be an array).
$this->db->where(array('blog_id'=>123))->pop('comments')->update('blog_posts'); $this->db->where(array('blog_id'=>123))->pop(array('comments', 'viewed_by'))->update('blog_posts');
$this->db->batch_insert()

Insert a multiple new document into the passed collection. Forexample you need to insert one billion record to database at once.
$this->db->batch_insert('foo',  $data = array();
$this->db->mongo_instance()

Returns to Mongodb instance of object.

Store a gridfs file using mongo instance.
$gridFS = $this->db->mongo_instance()->getGridFS();  // get a MongoGridFS instance
$id     = $gridFS->storeFile($filepath, array(
                                                  'filename' => uniqid(time()), 
                                                  'filetype' => $_FILES['field]['type'],
                                                  'filegroup'=> 'profile-picture',
                                                  'caption'  => 'Profile Picture'));
 echo $id;

Remove a gridfs file using mongo instance.
$gridFS = $this->db->mongo_instance()->getGridFS();
$gridFS->remove(array('user_id' => new MongoId($this->auth->data('_id')), 'filegroup' => 'profile-picture'));