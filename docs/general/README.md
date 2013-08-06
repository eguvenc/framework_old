
#General Topics

## Obullo URLs
By default, URLs in Obullo are designed to be search-engine and human friendly. Rather than using the standard "query string" approach to URLs that is synonymous with dynamic systems, Obullo uses a segment-based approach:
``` php
example.com/{module}/news/article/my_article
```
### Removing the index.php file
------
By default, the **index.php** file will be included in your URLs:
```php
example.com/index.php/module/news/article/my_article
```
### Apache HTTP Server
------
If you use Apache HTTP Server you can easily remove this file by using a **.htaccess** file with some simple rules. Here is an example of such a file, using the **"negative"** method in which everything is redirected except the specified items:
```php
DirectoryIndex index.php
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule (.*)/public/(.*) modules/$1/public/$2 [L]
RewriteCond $1 !^(index\.php|obullo|modules|application|public)
RewriteRule ^(.*)$ ./index.php/$1 [L,QSA]
```
In the above example, any HTTP request other than those for index.php, **/public** folder, and **robots.txt** is treated as a request for your index.php file.

**RewriteCond %{REQUEST_FILENAME} !-d**
If the request is for a real directory (one that exists on the server), index.php isn't served.

**RewriteCond %{REQUEST_FILENAME} !-f**
If the request is for a file that exists already on the server, index.php isn't served.

**RewriteRule ^(.*)$ /index.php**
All other requests are sent to index.php.

### Nginx HTTP Server
------
Removing index.php file also easy in Nginx, add below the codes to your vhost file like this. Change the all **"/var/www/obullo.com/public"** texts as your web server root path.
```php
server {
        limit_conn   myzone  10;
        listen       80;
        server_name  obullo.com www.obullo.com;

        #charset utf-8;

        access_log  /var/www/obullo.com/log/host.access.log  main;
        error_log   /var/www/obullo.com/log/host.error.log;

        root   /var/www/obullo.com/public;
        index  index.php index.html index.htm;

        # START OBULLO FRAMEWORK REWRITE RULES
        # ( Obullo URI_PROTOCOL should be REQUEST_URI
        # / application /config / uri_protocol = REQUEST_URI )

        # enforce NO www
        if ($host ~* ^www\.(.*))
        {
                set $host_without_www $1;
                rewrite ^/(.*)$ $scheme://$host_without_www/$1 permanent;
        }

        # canonicalize Obullo url end points
        # if your default controller is something other than "welcome" you should change the following
        if ($request_uri ~* ^(/welcome(/index)?|/index(.php)?)/?$)
        {
                rewrite ^(.*)$ / permanent;
        }

        # removes trailing "index" from all controllers
        if ($request_uri ~* index/?$)
        {
                rewrite ^/(.*)/index/?$ /$1 permanent;
        }

        # removes trailing slashes (prevents SEO duplicate content issues)
        if (!-d $request_filename)
        {
                rewrite ^/(.+)/$ /$1 permanent;
        }

        # removes access to "obullo" folder, also allows a "System.php" controller
        if ($request_uri ~* ^/obullo)
        {
                rewrite ^/(.*)$ /index.php?/$1 last;
                break;
        }

        # unless the request is for a valid file (image, js, css, etc.), send to bootstrap
        if (!-e $request_filename)
        {
                rewrite ^/(.*)$ /index.php?/$1 last;
                break;
        }

        # END OBULLO FRAMEWORK REWRITE RULES

        # error_page  404              /404.html;
        # location = /404.html {
        #   root   /usr/share/nginx/html;
        # }

        # redirect server error pages to the static page /50x.html
        #
        # error_page   500 502 503 504  /50x.html;
        # location = /50x.html {
        #    root   /usr/share/nginx/html;
        # }

        # proxy the PHP scripts to Apache listening on 127.0.0.1:80
        #
        #location ~ \.php$ {
        #    proxy_pass   http://127.0.0.1;
        #}

        # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000

        location ~ \.php$ {
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME /var/www/obullo.com/public/$fastcgi_script_name;
            include        fastcgi_params;
        }

        # deny access to .htaccess files, if Apache's document root
        # concurs with nginx's one
        #
        location ~ /\.ht {
            deny  all;
        }
    }
```
**Edit your config.php**

Finally you should do some changes in your **application/config/config.php** file.
```php
$config['index_page'] = "";
$config['uri_protocol'] = "REQUEST_URI";   // QUERY_STRING
```
If you can't get uri requests try to change your uri protocol which is defined **application/config/config.php** file.

### Adding a URL Suffix
------
In your **config/config.php** file you can specify a suffix that will be added to all URLs generated by Obullo. For example, if a URL is this:
```php
example.com/index.php/shop/products/view/shoes
```
You can optionally add a suffix, like **.html**, making the page appear to be of a certain type:
```php
example.com/index.php/shop/products/view/shoes.html
```
### Enabling Query Strings
------
In some cases you might prefer to use query strings URLs:
```php
index.php?d=shop&c=products&m=view&id=345
```
Obullo optionally supports this capability, which can be enabled in your application/config.php file. If you open your config file you'll see these items:
```php
$config['enable_query_strings'] = FALSE;
$config['directory_trigger']  = 'd';
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
```
If you change "enable_query_strings" to TRUE this feature will become active. Your controllers and functions will then be accessible using the "trigger" words you've set to invoke your directory, controllers and methods:
```php
index.php?d=directory&c=controller&m=method
```
**_Please note:_** If you are using query strings you will have to build your own URLs, rather than utilizing the URL helpers (and other helpers that generate URLs, like some of the form helpers) as these are designed to work with segment based URLs.
### URI Extensions
-------
You can use uri extensions when you use ajax, xml, rss, json.. requests, you can dynamically change the application behaviours using uri extensions. Also this functionality will help you to create friendly urls.
```php
example.com/module/class/post.json
```
You can define allowed extensions from your application/config/config.php file, default allowed URI extensions listed below.
- php
- html
- json
- xml
- raw
- rss
- ajax

Using URI Class $this->uri->extension(); function you can grab the called URI extension.
```php
switch($this->uri->extension())
{
    case 'json':
        echo json_encode($data);
    break;
    
    case 'html':
        echo $data;
    break;
}
```
## Controllers
Controllers are the heart of your application, as they determine how HTTP requests should be handled.

- [What is a Controller?](#what-is-a-controller)
- [Hello World](#lets-try-it-hello-world)
- [Functions](#functions)
- [Passing URI Segments to Your Functions](#passing-uri-segments-to-your-functions)
- [Defining a Default Controller](#defining-a-default-controller)
- [Remapping Function Calls](#remapping-function-calls)
- [Controlling Output Data](#processing-output)
- [Private Functions](#private-functions)
- [Class Constructors](#class-constructors)
- [Reserved Function Names](#reserved-function-names)

### What is a Controller? 
------
**A Controller is simply a class file that is named in a way that can be associated with a URI.**

Consider this URI:
```php
example.com/index.php/blog/start
```
In the above example, Obullo would attempt to find a MODULE named <dfn>/blog</dfn> in the MODULES folder and it attempt the find a controller named <dfn>start.php</dfn> in the /controllers folder and load it.

**When a controller's name matches the second segment of a URI, it will be loaded.**

### Let's try it: Hello World! 
-------
Let's create a simple controller so you can see it in action. Create a directory called <samp>blog</samp> in the modules folder

Then create <samp>controllers, helpers, models, views</samp> folders and which folders you need.
```php
-  application
    + config
-  modules
    + welcome
    - blog
        - controllers
           start.php
          helpers
          models
          views
```
Using your text editor, create file called <dfn>start.php</dfn> in the <dfn>blog/controllers</dfn> folder, and put the following code in it:
```php
<?php namespace ob;

Class Start extends Controller 
{
    public function index()
    {
        echo 'Hello World !';
    }
    
}
?> 
```
Then save the file to your <dfn>modules/blog/controllers/</dfn> folder.

Now visit the your site using a URL similar to this:
```php
example.com/index.php/blog/start
```
If you did it right, you should see <samp>Hello World!</samp>.

**Note**: Class names must start with an uppercase letter. In other words, this is valid:
```php
<?php namespace ob;
Class Start extends Controller
{

}
?>
```
This is **not** valid:
```php
<?php
namespace ob;
Class start extends Controller
{
    
}
?>
```
Also, always make sure your controller <dfn>extends</dfn> the parent controller class so that it can inherit all its functions.

### Functions 
------
In the above example the function name is <dfn>index()</dfn>. The "index" function is always loaded by default if the **third segment** of the URI is empty. Another way to show your "Hello World" message would be this:
```php
example.com/index.php/blog/start/index/
```
**The third segment of the URI determines which function in the controller gets called.**

Let's try it. Add a new function to your controller:
```php
<?php namespace ob;
Class Start extends Controller
{
    public function index()
    {
        echo 'Hello World !';
    }
    
    public function comments()
    {
        echo 'Dummy Comments .. !'; 
    }
    
}  
?>
```
Now load the following URL to see the <dfn>comment</dfn> function:
```php
example.com/index.php/blog/start/comments/
```
You should see your new message.

### Passing URI Segments to your Functions 
------
If your URI contains more then two segments they will be passed to your function as parameters.

For example, lets say you have a URI like this:
```php
example.com/index.php/shop/products/cars/classic/123
```
Your function will be passed URI segments number 4 and 5 ("classic" and "123"):
```php
<?php namespace ob;

Class Products extends Controller
{
    public function cars($type, $id)
    {
        echo $type;           // Output  classic 
        echo $id;                // Output  123 

        echo $this->uri->segment(4);    // Output  123 
        
    }
} 
?>
```
**Important:** If you are using the [URI Routing](http://obullo.com/user_guide/en/1.0.1/uri-routing.html) feature, the segments passed to your function will be the re-routed ones.
### Defining a Default Controller
------
Obullo can be told to load a default controller when a URI is not present, as will be the case when only your site root URL is requested. To specify a default controller, open your <dfn>application/config/routes.php</dfn> file and set this variable:
```php
$route['default_controller'] = 'blog/start';
```
Where <var>Blog</var> is the name of the <samp>directory</samp> and <var>Start</var> controller class you want used. If you now load your main index.php file without specifying any URI segments you'll see your Hello World message by default.

### Remapping Function Calls
-------
As noted above, the second segment of the URI typically determines which function in the controller gets called. Obullo permits you to override this behavior through the use of the <samp>_remap()</samp> function:
```php
public function _remap()
{
    // Some code here...
}
```
**Important:** If your controller contains a function named <samp>_remap()</samp>, it will **always** get called regardless of what your URI contains. It overrides the normal behavior in which the URI determines which function is called, allowing you to define your own function routing rules.
The overridden function call (typically the second segment of the URI) will be passed as a parameter the <samp>_remap()</samp> function:
```php

public function _remap($method)
{
    if ($method == 'some_method')
    {
        $this->$method();
        
    } else 
    {
        $this->default_method();
    }
}
```
### Processing Output
------
Obullo has an output class that takes care of sending your final rendered data to the web browser automatically. More information on this can be found in the [Views](http://obullo.com/user_guide/en/1.0.1/views.html) and [Output](http://obullo.com/user_guide/en/1.0.1/output-class.html) class pages. In some cases, however, you might want to post-process the finalized data in some way and send it to the browser yourself. Obullo permits you to add a function named <dfn>_output()</dfn> to your controller that will receive the finalized output data.

**Important:** If your controller contains a function named <samp>_output()</samp>, it will always be called by the output class instead of echoing the finalized data directly. The first parameter of the function will contain the finalized output.

Here is an example:
```php
public function _output($output)
{
    echo $output;
}
```
Please note that your <dfn>_output()</dfn> function will receive the data in its finalized state. Benchmark and memory usage data will be rendered, cache files written (if you have caching enabled), and headers will be sent (if you use that [feature](http://obullo.com/user_guide/en/1.0.1/output-class.html)) before it is handed off to the _output() function. If you are using this feature the page execution timer and memory usage stats might not be perfectly accurate since they will not take into acccount any further processing you do. For an alternate way to control output <em>before</em> any of the final processing is done, please see the available methods in the [Output Class](http://obullo.com/user_guide/en/1.0.1/output-class.html).
### Private Functions
------
In some cases you may want certain functions hidden from public access. To make a function private, simply add php native <samp>private</samp> property and it will not be served via a URL request. For example, if you were to have a function like this:
```php
private function utility()
{
    // some code
}
```
Trying to access it via the URL, like this, will not work and Obullo will show "404 page not found" error:
```php
example.com/index.php/blog/start/utility/
```
### Class Constructors
------
If you intend to use a constructor in any of your Controllers, you **MUST** place the following line of code in it:
```php
parent::__construct();
```
The reason this line is necessary is because your local constructor will be overriding the one in the parent controller class so we need to manually call it.
```php
<?php namespace ob;
class Start extends Controller
{
    function __construct()
    {
        parent::__construct();
    }
}
?>
```
Constructors are useful if you need to set some default values, or run a default process when your class is instantiated. Constructors can't return a value, but they can do some default work.

### Reserved Function Names
------
Since your controller classes will extend the main application controller you must be careful not to name your functions identically to the ones used by that class, otherwise your local functions will override them. See [Reserved Names](#reserved-names) for a full list.

### That's it!
------
That, in a nutshell, is all there is to know about controllers.
## Working with Parent Controllers

### Parent Controllers
------
You can define your custom Controllers, The Parent Controllers are the parent of your main controller file, it control the <strong>extra jobs</strong> in the application. There are <strong>two</strong> Libraries folder called <strong>/libraries</strong> and it can be locate in your MODULES or APPLICATION directory.

### Application Parent Controllers
------
All your controllers in the framework simply can extend to My_Controller class which is located in your <dfn>app/libraries</dfn> folder. Obullo autoloaders will load the your parent controller simply when you extend to it.

This is an example parent controller we put it to <dfn>app/libraries</dfn> folder.
```php
<?php namespace ob;
Class My_Controller extends Controller
{                                     
    public function __construct()
    {
        parent::__construct();

        new helpername\start();
    }
}
?>
```
After that you need extend your custom controller like below the example by the way you can change the My_Contoller name anything you want.
```php
<?php namespace ob;
Class Start extends My_Controller
{
    function __construct()
    {
        parent::__construct(); 
    }
    
    public function index()
    {
        vi\set_var('title',  'Hello World !');
        
        vi\get('example',  '', FALSE);
    }
    
}
?> 
```
### Module Parent Controllers
------
You can define a Module Controller in current module. Obullo autoloaders will load it from your <dfn>modulename/libraries</dfn> folder simply when you extend to it.
```php
<?php namespace ob;
Class Welcome_Controller extends Controller
{                                     
    public function __construct()
    {
        parent::__construct();

        new helpername\start();
    }
}
?>
```
After that you need extend your custom controller like below the example
```php
<?php namespace ob;
Class Start extends Welcome_Controller
{
    function __construct()
    {
        parent::__construct(); 
    }
    
    public function index()
    {
        vi\set_var('title',  'Hello World !');
        
        vi\get('example', '', FALSE);
    }
    
}
?> 
```
You can find the Welcome Controller example in <dfn>modules/welcome/libraries</dfn> folder.

### Reserved Names
------
In order to help out, Obullo uses a series of functions and names in its operation. Because of this, some names cannot be used by a developer. Following is a list of reserved names that cannot be used.

### Controller names
------
Since your controller classes will extend the main application controller you must be careful not to name your functions identically to the ones used by that class, otherwise your local functions will override them. The following is a list of reserved names. Do not name your controller functions any of these:

- Controller
- _instance()
- index()
- _remap()
- _output()
- _output_hmvc()

#### Functions
------
- ob_include_files()
- ob_set_headers()
- ob_system_run()
- ob_system_close()
- is_really_writable()
- set_status_header()
- ob_autoload()
- load_class()
- get_static()
- get_config()
- config_item()
- db_item()
- is_php()
- show_http_error()
- Obullo_ErrorTemplate()
- Obullo_ErrorHandler()
- show_error()
- show_404()
- log_me()
- lang()
- this()
- __merge_autoloaders()
- All Helper Functions

#### Variables
------
- $_ob
- $config
- $lang
- $routes

#### Reserved $GLOBALS variables
------

- $GLOBALS['d']
- $GLOBALS['c']
- $GLOBALS['m']
- $GLOBALS['s']

#### Constants
------
- DS
- EXT
- ROOT
- MODULES
- PHP_PATH
- FCPATH
- SELF
- BASE
- APP
- CMD
- TASK
- OBULLO_VERSION
##### File Constants

* FILE_READ_MODE
* FILE_WRITE_MODE
* DIR_READ_MODE
* DIR_WRITE_MODE
* FOPEN_READ
* FOPEN_READ_WRITE
* FOPEN_WRITE_CREATE_DESTRUCTIVE
* FOPEN_READ_WRITE_CREATE_DESTRUCTIVE
* FOPEN_WRITE_CREATE
* FOPEN_READ_WRITE_CREATE
* FOPEN_WRITE_CREATE_STRICT
* FOPEN_READ_WRITE_CREATE_STRICT
##### Database Constants

* param_null
* param_int
* param_str
* param_lob
* param_stmt
* param_bool
* param_inout
* lazy
* assoc
* num
* both
* obj
* row
* bound
* column
* as_class
* into
* func
* named
* key_pair
* group
* unique
* class_type
* serialize
* props_late
* ori_next
* ori_prior
* ori_first
* ori_last
* ori_abs
* ori_rel
## Views

A view is simply a web page, or a page fragment, like a header, footer, sidebar, etc. In fact, views can flexibly be embedded within other views (within other views, etc., etc.) if you need this type of hierarchy.

Views are never called directly, they must be loaded by a controller. Remember that in an MVC framework, the Controller acts as the traffic cop, so it is responsible for fetching a particular view. If you have not read the [Controllers](#controllers) page you should do so before continuing.

Using the example controller you created in the controller page, let's add a view to it.

### Creating a View
------
Using your text editor, create a file called <dfn>view_blog.php</dfn>, and put this in it:
```php
<h1>Welcome to my Blog!</h1>
```
Then save the file in your <dfn>modules/views/</dfn> folder.

Using your text editor, create a file called <dfn>header.php</dfn>, and put this in it:
```php
<html>
<head>
<title><?php echo vi/views('title'); ?></title>
</head>
<body>
```
Using your text editor, create a file called <dfn>footer.php</dfn>, and put this in it:
```php
</body>
</html>
```
### Loading a Module and Application View File
------
To load a view file you will use the following function:
```php
vi/views('filename');
```
<strong>Tip</strong>: This function normally loads a file as string. If you want to use include functionality, use it like this <samp>view('name', '', false);</samp>
### Creating Variables for Layouts
------
To load a global view file you will use the following function:
```php
vi/set_var('key', 'val');
```
You can store values multiple times...
```php
vi/set_var('title', 'Hello ');
vi/set_var('title', 'My ');
vi/set_var('title', 'Dear !');
```
echo view_var('title');   // Hello My Dear !
Now, open the controller file you made earlier called <dfn>blog/start.php</dfn>, and replace the echo statement with the view loading function:
```php
<?php namespace ob;
Class Start extends Controller
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        vi/set_var('title', 'Welcome to My Blog !');
        
        vi/get('view_blog', '', FALSE);     // it includes your local view file 
    }
}
?>
```
If you visit the your site using the URL you did earlier you should see your new view. The URL was similar to this:
```php
example.com/index.php/blog/start/
```
### Adding Dynamic Data to the View
------
Data is passed from the controller to the view by way of an <strong>array</strong> in the second parameter of the view loading function. Here is an example using an array:
```php
<?php

$data = array(
               'numbers' => array('1','2','3','4','5'),
               'message' => 'My Message'
          );

vi/get('view_blog', $data, FALSE);

?>
```
Let's try it with your controller file. Open it add this code:
```php
<?php namespace ob;
Class Start extends Controller
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {   
         $data = array(
               'numbers' => array('1','2','3','4','5'),
               'message' => 'My Message'
          );
        
        vi/get('view_blog', $data, FALSE);
    }
} 
?>
```
Now open your view_blog.php file and change the text to variables that correspond to the array keys in your data:
```php
echo vi/views('../header');

<h1>Welcome to my Blog!</h1>

Numbers:  <?php print_r($numbers); ?>
Message:  <?php echo $message; ?>

echo vi/views('../footer)';
```
Then load the page at the URL you've been using and you should see the variables replaced.

### Creating Loops
------
The data array you pass to your view files is not limited to simple variables. You can pass multi dimensional arrays, which can be looped to generate multiple rows. For example, if you pull data from your database it will typically be in the form of a multi-dimensional array.

Now open your local view file and create a loop:
```php
echo vi/views('../header)';

<h1>Welcome to my Blog!</h1>

Numbers:

<ul>
<?php foreach($numbers as $item):?>

<li><?php echo $item;?></li>

<?php endforeach;?>

echo vi/views('../footer)';
</ul>

Message:  <?php echo $message; ?>
```
### Loading Views from Common views folder!
------
```php
echo vi/views('../header');
echo vi/views('../footer');
```
<strong>Note:</strong> You'll notice that in the example above we are using PHP's alternative syntax. If you are not familiar with it you can read about it here.

### Fetching views as string or loading as include
------
There is a third optional parameter lets you change the behavior of the function so that it load file as include instead of return to string. This can be useful if you want to process the data in some way. If you set the parameter to false (boolean) it will load file as include.

View ( as string)
```php
$string = vi/views('myfile');  // Default framework behaviour
```
View ( as include)
```php
vi/views('myfile', '', FALSE);
```
For more functions look at View Helper file.
