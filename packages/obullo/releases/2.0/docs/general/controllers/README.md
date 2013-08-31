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

In the above example, Obullo would attempt to find a MODULE named <dfn>/blog</dfn> in the MODS folder and it attempt the find a controller named <dfn>start.php</dfn> in the /controllers folder and load it.

**When a controller's name matches the second segment of a URI, it will be loaded.**

### Let's try it: Hello World! <a name="lets-try-it-hello-world"></a>

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
<?php namespace Ob;

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
<?php namespace Ob;
Class Start extends Controller
{

}
?>
```

This is **not** valid:

```php
<?php
namespace Ob;
Class start extends Controller
{
    
}
?>
```

Also, always make sure your controller <dfn>extends</dfn> the parent controller class so that it can inherit all its functions.

### Functions <a name="functions"></a>

------

In the above example the function name is <dfn>index()</dfn>. The "index" function is always loaded by default if the **third segment** of the URI is empty. Another way to show your "Hello World" message would be this:

```php
example.com/index.php/blog/start/index/
```

**The third segment of the URI determines which function in the controller gets called.**

Let's try it. Add a new function to your controller:

```php
<?php namespace Ob;
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

### Passing URI Segments to your Functions <a name="passing-uri-segments-to-your-functions"></a>

------

If your URI contains more then two segments they will be passed to your function as parameters.

For example, lets say you have a URI like this:

```php
example.com/index.php/shop/products/cars/classic/123
```

Your function will be passed URI segments number 4 and 5 ("classic" and "123"):

```php
<?php namespace Ob;

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

**Important:** If you are using the URI Routing feature, the segments passed to your function will be the re-routed ones.

### Defining a Default Controller <a name="defining-a-default-controller"></a>

------

Obullo can be told to load a default controller when a URI is not present, as will be the case when only your site root URL is requested. To specify a default controller, open your <dfn>app/config/routes.php</dfn> file and set this variable:

```php
$route['default_controller'] = 'blog/start';
```

Where <var>Blog</var> is the name of the <samp>directory</samp> and <var>Start</var> controller class you want used. If you now load your main index.php file without specifying any URI segments you'll see your Hello World message by default.

### Remapping Function Calls <a name="remapping-function-calls"></a>

-------

As noted above, the second segment of the URI typically determines which function in the controller gets called. Obullo permits you to override this behavior through the use of the <samp>_remap()</samp> function:

```php
public function _remap()
{
    // Some code here...
}
```

**Important:** If your controller contains a function named <samp>remap()</samp> , it will **always** get called regardless of what your URI contains. It overrides the normal behavior in which the URI determines which function is called, allowing you to define your own function routing rules.
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

### Processing Output <a name="processing-output"></a>

------

Obullo has an output class that takes care of sending your final rendered data to the web browser automatically. More information on this can be found in the Views and Output class pages. In some cases, however, you might want to post-process the finalized data in some way and send it to the browser yourself. Obullo permits you to add a function named <dfn>_output()</dfn> to your controller that will receive the finalized output data.

**Important:** If your controller contains a function named <samp>_output()</samp>, it will always be called by the output class instead of echoing the finalized data directly. The first parameter of the function will contain the finalized output.

Here is an example:

```php
public function _output($output)
{
    echo $output;
}
```

Please note that your <dfn>_output()</dfn> function will receive the data in its finalized state. Benchmark and memory usage data will be rendered, cache files written (if you have caching enabled), and headers will be sent (if you use that feature) before it is handed off to the _output() function. If you are using this feature the page execution timer and memory usage stats might not be perfectly accurate since they will not take into acccount any further processing you do. For an alternate way to control output <em>before</em> any of the final processing is done, please see the available methods in the Output Class.

### Private Functions <a name="private-functions"></a>

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

### Class Constructors <a name="class-constructors"></a>

------

If you intend to use a constructor in any of your Controllers, you **MUST** place the following line of code in it:

```php
parent::__construct();
```

The reason this line is necessary is because your local constructor will be overriding the one in the parent controller class so we need to manually call it.

```php
<?php namespace Ob;
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

### Reserved Function Names <a name="reserved-function-names"></a>

------

Since your controller classes will extend the main application controller you must be careful not to name your functions identically to the ones used by that class, otherwise your local functions will override them. See Reserved Names for a full list.

### That's it!

------

That, in a nutshell, is all there is to know about controllers.

## Working with Parent Controllers

### Parent Controllers <a name="parent-controllers"></a>

------

You can define your custom Controllers, The Parent Controllers are the parent of your main controller file, it control the <strong>extra jobs</strong> in the application. There are <strong>two</strong> Libraries folder called <strong>/libraries</strong> and it can be locate in your MODS or APPLICATION directory.

### Application Parent Controllers <a name="application-parent-controllers"></a>

------

All your controllers in the framework simply can extend to My_Controller class which is located in your <dfn>app/libraries</dfn> folder. Obullo autoloaders will load the your parent controller simply when you extend to it.

This is an example parent controller we put it to <dfn>app/libraries</dfn> folder.

```php
<?php namespace Ob;
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
<?php namespace Ob;
Class Start extends My_Controller
{
    function __construct()
    {
        parent::__construct(); 
    }
    
    public function index()
    {
        vi\setVar('title',  'Hello World !');
        
        vi\get('example',  '', false);
    }
    
}
?> 
```

### Module Parent Controllers <a name="module-parent-controllers"></a>

------

You can define a Module Controller in current module. Obullo autoloaders will load it from your <dfn>modulename/libraries</dfn> folder simply when you extend to it.

```php
<?php namespace Ob;
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
<?php namespace Ob;
Class Start extends Welcome_Controller
{
    function __construct()
    {
        parent::__construct(); 
    }
    
    public function index()
    {
        vi\setVar('title',  'Hello World !');
        
        vi\get('example', '', false);
    }
    
}
?> 
```
You can find the Welcome Controller example in <dfn>modules/welcome/libraries</dfn> folder.