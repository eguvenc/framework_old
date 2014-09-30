## Controllers <a name="controllers"></a>

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

### What is a Controller? <a name="what-is-a-controller"></a>

------

**A Controller is simply a class file that is named in a way that can be associated with a URI.**

Consider this URI:

```php
example.com/index.php/blog/start
```

In the above example, framework would attempt to find a MODULE named <kbd>/blog</kbd> in the PUBLIC folder and it attempts to find a controller named <kbd>start.php</kbd> in the /controller folder and load it.

**When a controller's name matches the second segment of a URI, it will be loaded.**

### Let's try it: Hello World! <a name="lets-try-it-hello-world"></a>

-------

Let's create a simple controller so you can see it in action. Create a directory called <kbd>blog</kbd> in the public folder

Then create <kbd>controller</kbd>  and <kbd>view</kbd> folders.

```php
-  app
    + config
-  public
    + welcome
    - home
       - controller
           home.php
         view
```

Using your text editor, create folder <kbd>home/controller</kbd> then create a file called <kbd>home.php</kbd> in the <kbd>home/controller</kbd> folder, and put the following code in it:

```php
<?php
/**
 * $c home
 * @var Controller
 */
$c = new Controller(function(){
    // __construct

});

$c->func('index', function(){
    
    echo 'Hello World !';
    
});

/* End of file home.php */
/* Location: .public/home/controller/home.php */
```

Then save the file to your <kbd>public/blog/controllers/</kbd> folder.

Now visit your site using a URL similar to this:

```php
demo-blog.com/index.php/blog/start
```

### Functions <a name="functions"></a>

------

In the above example the function name is <kbd>index()</kbd>. The "index" function is always loaded by default if the **third segment** of the URI is empty. Another way to show your "Hello World" message would be this:

```php
demo-blog.com/index.php/blog/start/index/
```

**The third segment of the URI determines which function in the controller gets called.**

Let's try it. Add a new function to your controller:



```php
<?php
/**
 * $c start
 * @var Controller
 */
$c = new Controller(function(){
    // __construct

});

$c->func('comment', function(){
    
    echo 'Dummy Comments .. !'; 
    
});
```

Now load the following URL to see the <kbd>comments</kbd> function:

```php
demo-blog.com/index.php/comments/
```

You should see your new message.

### Passing URI Segments to your Functions <a name="passing-uri-segments-to-your-functions"></a>

------

If your URI contains more then two segments they will be passed to your function as parameters.

For example, lets say you have a URI like this:

```php
shop.com/index.php/products/cars/classic/123
```

Your function will be passed URI segments number 4 and 5 ("classic" and "123"):

```php
<?php

Class Products extends Controller
{
    public function cars($type, $id)
    {
        echo $type;           // Output  classic 
        echo $id;                // Output  123 

        echo $this->uri->segment(4);    // Output  123 
        
    }
} 
```

**Important:** If you are using the URI Routing feature, the segments passed to your function will be the re-routed ones.

### Defining a Default Controller <a name="defining-a-default-controller"></a>

------

Framework can be told to load a default controller when a URI is not present, as will be the case when only your site root URL is requested. To specify a default controller, open your <kbd>app/config/routes.php</kbd> file and set this variable:

```php
$route['default_controller'] = 'blog/start';
```

Where <var>Blog</var> is the name of the <kbd>directory</kbd> and <var>Start</var> controller class you want to use. If you now load your main index.php file without specifying any URI segments you'll see your Hello World message by default.

### Remapping Function Calls <a name="remapping-function-calls"></a>

-------

As noted above, the second segment of the URI typically determines which function in the controller gets called. Framework permits you to override this behavior through the use of the <kbd>_remap()</kbd> function:

```php
public function _remap()
{
    // Some code here...
}
```

**Important:** If your controller contains a function named <kbd>remap()</kbd> , it will **always** get called regardless of what your URI contains. It overrides the normal behavior in which the URI determines which function is called, allowing you to define your own function routing rules.
The overridden function call (typically the second segment of the URI) will be passed as a parameter the <kbd>_remap()</kbd> function:

```php

public function _remap($method)
{
    if ($method == 'some_method')
    {
        $this->$method();
        
    } else 
    {
        $this->defaultMethod();
    }
}
```

### Processing Output <a name="processing-output"></a>

------

Framework has an output class that takes care of sending your final rendered data to the web browser automatically. More information on this can be found in the Views and Output class pages. In some cases, however, you might want to post-process the finalized data in some way and send it to the browser yourself. Framework permits you to add a function named <kbd>_output()</kbd> to your controller that will receive the finalized output data.

**Important:** If your controller contains a function named <kbd>_output()</kbd>, it will always be called by the output class instead of echoing the finalized data directly. The first parameter of the function will contain the finalized output.

Here is an example:

```php
public function _output($output)
{
    echo $output;
}
```

Please note that your <kbd>_output()</kbd> function will receive the data in its finalized state. Benchmark and memory usage data will be rendered, cache files written (if you have caching enabled), and headers will be sent (if you use that feature) before it is handed off to the _output() function. If you are using this feature the page execution timer and memory usage stats might not be perfectly accurate since they will not take into acccount any further processing you do. For an alternate way to control output <em>before</em> any of the final processing is done, please see the available methods in the Output Class.

### Private Functions <a name="private-functions"></a>

------

In some cases you may want certain functions hidden from public access. To make a function private, simply add php native <kbd>private</kbd> property and it will not be served via a URL request. For example, if you were to have a function like this:

```php
private function utility()
{
    // some code
}
```

Trying to access it via the URL, like this, will not work and framework will show "404 page not found" error:

```php
example.com/index.php/blog/start/utility/
```

### Class Constructors <a name="class-constructors"></a>

------

If you intend to use a constructor in any of your Controllers, you **MUST** place the following line of code in it:

```php
parent::__construct();
```

The reason this line is necessary is because your local constructor will be overriding the one in the parent controller class so we need to manually call it.

```php
<?php
class Start extends Controller
{
    function __construct()
    {
        parent::__construct();
    }
}
```

Constructors are useful if you need to set some default values, or run a default process when your class is instantiated. Constructors can't return a value, but they can do some default work.

### Reserved Function Names <a name="reserved-function-names"></a>

------

Since your controller classes will extend the main application controller you must be careful not to name your functions identically to the ones used by that class, otherwise your local functions will override them. See Reserved Names for a full list.

### That's it!

------

That, in a nutshell, is all there is to know about controllers.

###Extending To Your Controllers

Put your controller to <kbd>classes/mycontroler</kbd> folder. When you extend to it framework will automatically load it.

```php
<?php 
Class Start extends MyController
{
    function __construct()
    {
        parent::__construct(); 
    }
    
    public function index()
    {
        setVar('title',  'Hello World !');
        
        view('example');
    }
    
}
```

### "One Public Method Per Controller" Rule