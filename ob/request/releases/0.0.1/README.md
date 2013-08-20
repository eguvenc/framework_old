## About HMVC

------

About HMVC structure you can find more information in [Advanced Topics / HMVC](/docs/advanced/#hmvc) section. Obullo's HMVC library support just <b>internal</b> requests at this time.

### Calling HMVC Requests

------

To start using HMVC libraries first you should load request helper file.

```php
new request\start();
```

After loading request helper file hmvc functions will be available. You can call hmvc functions in <samp>controller, model, parent controllers and view</samp> files.

```php
$request = request('news/articles/index/412');

echo $request->exec();   // hmvc output will return to string.
```

### Ouick Access

------

Normally first parameter assigned for request method but if you not choose a method , Obullo request helper will do atuomatically $_GET request don't forget Obullo also store get and post data into $_REQUEST global variable.

```php
echo request('blog/blog/read')->exec(); 
```

Quick Decoding JSON Format 

```php
$row = request('module/controller/method')->decode('json')->exec();

echo $row->key; // output value
```

### HMVC Requests in Sub Modules

------

If you want to call a HMVC request in the sub.module, you need to provide sub.modulename otherwise Obullo will call a request outside of your sub.module folder.

```php
echo request('sub.module/module/controller/method')->exec();
```

#### request(Method = 'GET', $uri = ' ', $params (mixed), $cache_time = 0')

You must use first and second parameters third and fourth parameters is optional.


### Available Query Methods

------

<ul>
    <li>POST</li>
    <li>GET</li>
    <li>UPDATE</li>
    <li>DELETE</li>
    <li>PUT ( When we use PUT method we provide data as string using third parameter instead of array. )</li>
</ul>

### Sending POST and GET Data

------

You can set post or get data by manually.

POST data example

```php
request('POST', 'blog/blog/write',  array('article' => 'content blabla'))->exec();  // data must be array
```

GET data example

```php
$request = request('GET', 'blog/blog/write',  array('article' => 'content blabla'));  // data must be array

echo $request->exec();
```


### GET data with Query String

------

You enter query strings obullo will parse it simply as get data paramaters.

```php
echo request('/api?query=SELECT * FROM users LIMIT 100')->exec();
```

### Output Caching For Hmvc Requests

------

You can do cache for your static hmvc requests. When a hmvc request called the first time, the cache file will be written to your <samp>app/core/cache</samp> folder. You can learn more details about [ouput caching](#/docs/advanced/caching-and-compression).

```php
echo request('blog/blog/read', array(), $cache_time = 100)->exec(); 
```

If you set <b>cache_time = 0</b> hmvc function will delete your old cached file for each hmvc requests. 

```php
$request = request('blog/blog/read', array(), 0);   // cache file will be deleted at next page refresh.

echo $request->exec();
```

### Using Hmvc Class Functions

------

You can use Hmvc Class methods directly using second parameter set to FALSE. 

```php
$request = request('/captcha/create', FALSE);

$request->setMethod('get', $params = array());
$request->cache(0);
$request->setServer('key', 'val');  // Send $_SERVER headers

echo $request->exec();
```php

#### $request->noLoop();

Some users some times use the HMVC requests in the [parent controllers](/docs/general/#working-with-parent-controllers) in this case normally a HMVC library do a unlimited loop and this may cause server crashes, beware if you use hmvc requests in parent controllers you have to use noLoop(); method for each requests.

```php
echo request('blog/blog/read')->noLoop()->exec();
```

**Critical:** Hmvc requests *rarely* in use parent controllers but don't use hmvc requests without *noLoop();* method when you use them in any *parent controllers* otherwise this may cause server crashes.

### Examples

------

<?php
Class Start extends Controller {
    
    function __construct()
    {   
        parent::__construct();

        new request\start();
    }           
    
    public function index()
    {   
        echo request('blog/read/18282/')->exec();
    }

}

/* End of file start.php 
Location: .application/welcome/controllers/start.php */
```

and <samp>blog/blog/controllers/blog.php</php> file should be like this.

```php
<?php
Class Blog extends Controller {
    
    function __construct()
    {   
        parent::__construct();

        new Model\Blog();
    }           
    
    public function read($article_id)
    {
        $row = $this->blog->getArticle($article_id);   
        
        echo $row->article;  // hmvc request output must be return to string.
    }

}

/* End of file start.php */
Location: .application/blog/blog/controllers/blog.php */
```