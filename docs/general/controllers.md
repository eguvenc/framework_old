Controllers
======
Controllers are the heart of your application, as they determine how HTTP requests should be handled.

- [What is a Controller?](#what)
- [Hello World](#hello)
- [Functions](#functions)
- [Passing URI Segments to Your Functions](#passinguri)
- [Defining a Default Controller](#default)
- [Remapping Function Calls]()
- [Controlling Output Data]()
- [Private Functions]()
- [Class Constructors]()
- [Reserved Function Names]()

What is a Controller? {#what}
------
**A Controller is simply a class file that is named in a way that can be associated with a URI.**

Consider this URI:
```php
example.com/index.php/blog/start
```
In the above example, Obullo would attempt to find a MODULE named <dfn>/blog</dfn> in the MODULES folder and it attempt the find a controller named <dfn>start.php</dfn> in the /controllers folder and load it.

**When a controller's name matches the second segment of a URI, it will be loaded.**

Let's try it: Hello World! {#hello}
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
<?php
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
<?php
Class Start extends Controller
{

}
?>
```
This is **not** valid:
```php
<?php
Class start extends Controller
{
    
}
?>
```
Also, always make sure your controller <dfn>extends</dfn> the parent controller class so that it can inherit all its functions.

Functions {#functions}
------
In the above example the function name is <dfn>index()</dfn>. The "index" function is always loaded by default if the **third segment** of the URI is empty. Another way to show your "Hello World" message would be this:
```php
example.com/index.php/blog/start/index/
```
**The third segment of the URI determines which function in the controller gets called.**

Let's try it. Add a new function to your controller:
```php
<?php 
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

Passing URI Segments to your Functions {#passinguri}
------
If your URI contains more then two segments they will be passed to your function as parameters.

For example, lets say you have a URI like this:
```php
example.com/index.php/shop/products/cars/classic/123
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
?>
```
**Important:** If you are using the [URI Routing](http://obullo.com/user_guide/en/1.0.1/uri-routing.html) feature, the segments passed to your function will be the re-routed ones.
Defining a Default Controller
------
Obullo can be told to load a default controller when a URI is not present, as will be the case when only your site root URL is requested. To specify a default controller, open your <dfn>application/config/routes.php</dfn> file and set this variable:
```php
$route['default_controller'] = 'blog/start';
```
Where <var>Blog</var> is the name of the <samp>directory</samp> and <var>Start</var> controller class you want used. If you now load your main index.php file without specifying any URI segments you'll see your Hello World message by default.

Remapping Function Calls
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
Processing Output
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
Private Functions
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
Class Constructors
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
?>
```
Constructors are useful if you need to set some default values, or run a default process when your class is instantiated. Constructors can't return a value, but they can do some default work.

Reserved Function Names
------
Since your controller classes will extend the main application controller you must be careful not to name your functions identically to the ones used by that class, otherwise your local functions will override them. See [Reserved Names](http://obullo.com/user_guide/en/1.0.1/reserved-names.html) for a full list.

That's it!
------
That, in a nutshell, is all there is to know about controllers.