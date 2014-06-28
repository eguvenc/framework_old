### Uri Routing ( Router Class )

The router class allows you to remap the URLs. 

**Note:** This class is initialized automatically by the system so there is no need to do it manually.

**Note:** This class is a <kbd>component</kbd> defined in your package.json. You can <kbd>replace components</kbd> with third-party packages.

------

Typically there is a one-to-one relationship between a URL string and its corresponding <kbd>/module/controller/method</kbd>. The segments in a URI normally follow this pattern:

```php
example.com/module/class/function/id/
```

In some instances, however, you may want to remap this relationship so that a different class/function can be called instead of the one corresponding to the URL.

```php
example.com/index.php/shop/show/product/1
```

For example, lets say you want your URLs to have this prototype:

```php
example.com/shop/product/1/
example.com/shop/product/2/
example.com/shop/product/3/
example.com/shop/product/4/
```

Normally the second segment of the URL is reserved for the class name (show), but in the example above it instead has a product. To overcome this, router allows you to remap the URI handler.

### Setting Your Routing Rules

------

Routing rules are defined in your <kbd>app/config/routes.php</kbd> file. In it you'll see an array called <var>$routes</var> that permits you to specify your own routing criteria. Routes can be specified using either <kbd>/wildcards</kbd> or <kbd>Regular Expressions</kbd>

Setting your MODULE routing rules

------

Sub module available uri routing example

```php
example.com/submodule/module/class/function/id/
```

You can create routing rules for the current module, you just need to create a <var>routes.php</var> in your module <kbd>/config</kbd> directory. You must create an array called <kbd>$routes</kbd> that permits you to specify your own routing criteria for the module.

**Important:** If you want to define routing rules for a module your module name and routing rules' first segment must be the same , otherwise router class can't parse your route settings . Look at the example.

```php
$routes['modulename'] = 'modulename/users/login';   
```

### Wildcards

-------

A typical wildcard route might look something like this:

```php
$routes['shop/product/:num'] = "shop/show/product";
```

In a route, the array key contains the URI to be matched, while the array value contains the destination it should be re-routed to. In the above example, if the literal word "shop" is found in the <kbd>first</kbd> segment of the URL, and "product" is found in the <kbd>second</kbd> segment and a number is found in the <kbd>third</kbd> segment then "shop" directory and the "product" class are instead used.

You can match literal values or you can use two wildcard types:

```php
:num
:any
```
<b>:num</b> will match a segment containing only numbers.

<b>:any</b> will match a segment containing any character.

**Note:** Routes will run in the order they are defined. Higher routes will always take precedence over lower ones.

### Examples

------

Here are a few routing examples:

```php
$routes['blog'] = "blog/start";
```

A URL containing the word "blog" in the first segment will be remapped to the "blog" directory and "start" class.

```php
$routes['blog/users'] = "blogs/start/users/34";
```

A URL containing the segments blog/users will be remapped to the "blogs" directory , "start" class and the "users" method. The ID will be set to "34".

```php
$routes['shop/product/:any'] = "shop/show/product";
```
A URL with "shop/product" as the first segment, and anything in the second will be remapped to the "show" class and the "product" method.

```php
$routes['product/(:num)'] = "shop/show/product/$1";
```

A URL with "product" as the first segment, and anything in the second will be remapped to the "shop" directory, "show" class and the "product" method passing in the match as a variable to the function.
We can tell the process via schema like this 

```php
// This is your url request           // This process will work in background
example.com/index.php/product/4
          _ _ _ _ _ _ _                   _ _ _ _ _ _ _ _
                |                                |
                |                                |
// This is your url mask               // This is your process value
$routes['product/(:num)']         =   "shop/show/product/$1";
     
                |                                 |
                |_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _|
                
                                |
                                |
                        // process in background 
                       "shop/show/product/4";      
```

**Important:** Do not use leading/trailing slashes.

### Regular Expressions

------

If you prefer you can use regular expressions to define your routing rules. Any valid regular expression is allowed, as are back-references.

**Note:** If you use back-references you must use the dollar syntax rather than the double backslash syntax.

A typical RegEx route might look something like this:

```php
$routes['products/([a-z]+)/(\d+)'] = "$1/id_$2";
```

In the above example, a URI similar to <kbd>shop/products/shirts/123</kbd> would instead call the <kbd>shirts</kbd> controller class and the <kbd>id_123</kbd> function.

You can also mix and match wildcards with regular expressions.

### A Real Example

------

When we prepare a User Guide pages we will have some <b>long urls</b> like this 

```php
http://example.com/user_guide/show/page/4/29/uri-routing.html
http://example.com/user_guide/show/chapter/1/base-information.html
http://example.com/user_guide/show.html
```

As you can see above the example we had a <b>user_guide</b> directory and show class, we want to <b>remove "show"</b> segment from uri and we did it like this.

```php
$routes['user_guide/page/(:num)/(:num)/:any']  = "user_guide/show/page/$1/$2";
$routes['user_guide/chapter/(:num)/:any']      = "user_guide/show/chapter/$1";
$routes['user_guide/chapters']  = "user_guide/show";
```

and result 

```php
http://example.com/user_guide/page/4/29/uri-routing.html
http://example.com/user_guide/chapter/1/base-information.html
http://example.com/user_guide/chapters.html
```

### Reserved Routes

------

There is a one reserved route:

```php
$routes['default_controller'] = 'welcome/index';
```

This route indicates which controller class should be loaded if the URI contains no data, which will be the case when people load your root URL. In the above example, the "start" class would be loaded. You are encouraged to always have a default route otherwise a 404 page will appear by default.

```php
$routes['404_override'] = 'errors/page_missing';
```
This route will tell the Router what URI segments to use if those provided in the URL cannot be matched to a valid route.

**Important:** The reserved routes must come before any wildcard or regular expression routes.