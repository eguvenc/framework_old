## Models <a name="models"></a>

Models are <strong>optionally</strong> available for those who want to use a more traditional MVC approach.

- [What is a Model?](#what-is-a-model)
- [Anatomy of a Model](#anatomy-of-a-model)
- [Loading a Model](#loading-a-model)
- [Auto-Loading a Model](#auto-loading-models)
- [Connecting to your Database](#connecting-to-your-database)

### What is a Model? <a name="what-is-a-model"></a>

------

Models are PHP classes that are designed to work with information in your database. For example, let's say you use Obullo to manage a blog. You might have a model class that contains functions to insert, update, and retrieve your blog data. Here is an example of what such a model class might look like:

```php
<?php namespace Model;
use Ob;
Class Model_blog extends Model
{
    function __constuct()
    {
        parent::__constuct();  // Call the Model constructor 
        new Db\Db();    // Connect to current database setting.
    }
    
    public function get_last_ten_entries()
    {
        $query = $this->db->get('blog_entries', 10);
        
        return $query->resultArray();
    }

    public function insert_entry()
    {
        $data = array(
        'title'   => i\post('title'),  // its like $_POST['title']  
        'content' => i\post('content'), 
        'date'    => time(),
        );
    
        $this->db->insert('blog_entries',$data);
    }

    public function update_entry()
    {
        $data = array(
        'title'   => i\post('title'), 
        'content' => i\post('content'), 
        'date'    => time(),
        );

        $this->db->where('id', i\post('id'));
        $this->db->update('blog_entries',$data);
    }
}
?>
```

<strong>Note:</strong> The functions in the above example use the [Active Record Class](/docs/database/active_record_class) database functions.

<strong>Note:</strong> Please don't use $_POST variables as directly. Use <samp>i\post();</samp> function instead of php native $_POST variables.We have a Input Helper for the secure inputs.

### Anatomy of a Model <a name="anatomy-of-a-model"></a>

------

Model classes are stored in your <dfn>app/models/</dfn> folder. They can be locate in your local model folder if you create a <dfn>/model</dfn> folder under the <dfn>app/directories/{controller}/</dfn> path.

The basic prototype for a model class is this:

```php 
namespace Model;
use Ob;

Class Model_name extends Model
{
    function __construct()
    {
        parent::__construct();
    }
}
```

Where <dfn>Model_name</dfn> is the name of your class. Class names <strong>must</strong> have the first letter capitalized with the rest of the name lowercase. Make sure your class extends the base Model class.

The file name will be a lower case version of your class name. For example, if your class is this:

```php 
namespace Model;
use Ob;

Class Model_user extends Model
{
    function __construct()
    {
        parent::__construct();
    }
}
```

Your file should be like this:

```php
app/models/model_user.php
```

### Loading a Model <a name="loading-a-model"></a>

------

Your models will typically be loaded and called from within your [controller](/docs/general/controllers) functions. To load a application model you will use the following function:

```php
-  application
    + config
-  modules
        + welcome
        - blog
            + controllers
            - models
                model_blog.php
            + views
```

As you can see above the directory structure, If your model is located in your modules folder, For example, if you have a model located at <dfn>modules/blog/models/</dfn> you'll load it using:

```php
new Model\Blog();   // this method load a model from modules/blog/models/ directory
```

If you want to load a model file from another module you can use two dot and directory separator like this

```php
new Model\Models\Blog();
```

Once loaded, you will access your model functions using an object with the same name as your class:

```php
new Model\Blog();

$this->model_name->function();
```

#### Using No Instantiate <a name="using-no-instantiate"></a>

If you set second param to FALSE, you can instantiate the your model manually.

```php
$user = new Model\User(false);

$user->save();
```

#### Subfolders <a name="subfolders"></a>

You can load model from subfolders

```php
new Model\SubFolder\Blog();
```

If you would like provide __construct parameters you can do it like this:

```php
$config['param1'] = 'hehe';
$config['param2'] = 'blabla';

new Model\User($config);
```

Here is an example of a controller, that loads a model, then serves a view:

```php
<?php namespace Model;
use Ob;
Class Start extends Controller
{
    function __construct()
    {
        parent::__construct();
        
        new Model\Blog(); 
    }
    
    public function index()
    {
        $query  = $this->Blog->get_last_ten_entries();
        
        $data['results'] = $query->resultArray();
        
        vi\setVar('body',  view('view_blog', $data));
        
        vi\getVar('layouts/blog');
    }
}
?>
```
and view file should be like this ..

```php
<?php foreach($results as $key => $row): ?>

<h1><?php echo $row['blog_title']; ?></h1>

<p><?php echo $row['blog_article']; ?></p>

<?php endforeach; ?> 
```

### Auto-loading Models <a name="auto-loading-models"></a>

-------

Open your app/config/autoload.php or create a autoload.php into <dfn>modules/modulename/config/autoload.php</dfn> file and define the model file

```php
$autoload['model']      = array('model1', 'model2', 'app/model', '../module/model');
```

### Connecting to your Database <a name="connecting-to-your-database"></a>

------

When a model is loaded it does  <strong>NOT</strong> connect automatically to your database. The following options for connecting are available to you:

You can connect using the database methods [described here](/docs/database/database-configuration-and-connect), either from within your Controller class or your Model class. You must declare <samp>new Db/Db()</samp> function in your model or controller class.

Putting this code into your Controller or Model __construct() function enough for the current database connection which is defined in the <dfn>app/config/database.php</dfn>

```php 
namespace Model;
use Ob;
Class Model_user extends Model
{
    function __construct()
    {
        parent::__construct();
        new Db/Db();
    }
    
    public function get_users()
    {
        $this->db->query(" .... ");
    }
    
}
```

If your second database connection setting before defined in <dfn>app/config/database.php</dfn> file like this

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

Then you can connect to <samp>db2</samp> database just providing the db variable like this

```php
new Db\Db("db2");

$this->db2->query(" .... ");
```

Also you can manually pass database connectivity settings via the first parameter of your new Db\Db() function:

```php
$config = array(
     'variable' => 'db',
     'hostname' => 'localhost',
     'username' => 'root',
     'password' => '',
     'database' => 'test_db',
     'driver' => 'mysql',
     'dbh_port' => '',
     'char_set' => 'utf8',
     'options'  => array( PDO::ATTR_PERSISTENT => true )
 );                                

 new Db/Db($config);

$this->db->query(  ....  );
```

If you are provide a dsn connection string you don't need to provide other parameters into <samp>$config</samp> data.

```php
$config = array(
     'variable' => 'db2',
     'char_set' => 'utf8',
     'dsn'      => 'mysql:host=localhost;port=3307;dbname=test_db;username=root;password=1234;'
 );

 new Db\Db($config);

$this->db2->query(  ....  );
```

<strong>Tip:</strong> You can reach your database connectivity settings by a [common function](/docs/advanced/common-functions) called <samp>db();</samp> .Look at below the code.

```php
echo db('hostname', 'db2');   // output localhost 
```

This will give you hostname parameter of second database connection setting which before defined in <dfn>app/config/database.php</dfn> file.

<strong>Note:</strong>If you want to use a library inside Model, we does not assign all libraries to a Model class so you must assign your library manually using <strong>this();</strong> word Like this ..

```php
<?php namespace Model;
use Ob;

Class Model_blog extends Model
{
    function __constuct()
    {
        parent::__constuct();  // Call the Model constructor
        new Db\Db();    // Connect to current database setting.
    }
    
    
    public function update()
    {
         // We assign all database objects to model class so you can use
        // $this variable like this
        
        $this->db->query(" ... ");
        
    }
    
    public function update_browser()
    {
         // We does not assign all library objects to model class so you can't use
        // $this variable, you must use this() function instead of $this 

        $ob = this();
        $ob->library->method();

        // if its a Obullo library you can use 
        // lib() function to get object instance like this
        $email = lib('Email');
        $email->init(' .. ');
        $email->from(' .. ');
        $email->to(' .. ');
        
        $email->send();
        
    }
    
    public function update_and_get()
    {  
        return sess_get('username');
        
    }

}
?>
```