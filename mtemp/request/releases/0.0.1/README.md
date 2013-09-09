## About HMVC

About HMVC structure you can find more information in Advanced Topics (/docs/advanced/hmvc) section. HMVC library support just <b>internal</b> requests.

### Calling HMVC Requests

------

Loading Request Helper

```php
new request\start();
```

After loading request helper file hmvc functions will be available. You can call hmvc functions in <kbd>controller, model and view</kbd> files.

```php
$request = request\get('news/articles/index/412');
```

### Ouick Access

------

Normally first parameter assigned for request method but if you not choose a method , request helper will do atuomatically $_GET request. Don't forget Hmvc also store get and post data into $_REQUEST global variable.

```php
echo request\get('blog/blog/read');
```


```php
$response = request\exec('get', 'module/controller/method');

echo $response; // output value
```

#### request\exec(Method = 'GET', $uri = ' ', $params (mixed), $cache_time = 0')

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
request\post('POST', 'blog/write',  array('article' => 'content blabla'));  // data must be array
```

GET data example

```php
$request = request\get('GET', 'blog/write',  array('article' => 'content blabla'));  // data must be array
```


### GET data with Query String

------

You enter query strings hmvc will parse it simply as get data paramaters.

```php
echo request\get('myapi?query=SELECT * FROM users LIMIT 100');
```

### Output Caching For Requests

------

You can do cache for your static hmvc requests. When a hmvc request called the first time, the cache file will be written to your <kbd>app/core/cache</kbd> folder. You can learn more details about ouput caching at <kbd>( /docs/advanced/caching-and-compression )</kbd> section.

```php
echo request\post('blog/blog/read', array(), $cache_time = 100);
```

If you set <b>cache_time = 0</b> hmvc function will delete your old cached file for each hmvc requests. 

```php
$request = request\get('blog/blog/read', array(), 0);   // cache file will be deleted at next page refresh.
```

### Examples

------

```php
<?php
Class Start extends Controller {
    
    function __construct()
    {   
        parent::__construct();

        new request\start();
    }           
    
    public function index()
    {   
        echo request\post('blog/read/18282/');
    }

}

/* End of file start.php 
Location: .app/welcome/controllers/start.php */
```

and <samp>blog/controllers/blog.php</php> file should be like this.

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

/* End of file blog.php */
Location: .modules/blog/controller/blog.php */
```