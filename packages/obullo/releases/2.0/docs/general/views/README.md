## Views <a name="views"></a>

A view is simply a web page, or a page fragment, like a header, footer, sidebar, etc. In fact, views can flexibly be embedded within other views (within other views, etc., etc.) if you need this type of hierarchy.

Views are never called directly, they must be loaded by a controller. Remember that in an MVC framework, the Controller acts as the traffic cop, so it is responsible for fetching a particular view. If you have not read the Controllers page you should do so before continuing.

Using the example controller you created in the controller page, let's add a view to it.

### Creating a View <a name="creating-a-view"></a>

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

### Creating Variables for Layouts <a name="creating-variables-for-layouts"></a>

------

To load a global view file you will use the following function:

```php
vi/setVar('key', 'val');
```

You can store values multiple times...

```php
vi/set_var('title', 'Hello ');
vi/set_var('title', 'My ');
vi/set_var('title', 'Dear !');
echo view_var('title');   // Hello My Dear !
```

Now, open the controller file you made earlier called <dfn>blog/start.php</dfn>, and replace the echo statement with the view loading function:

```php
<?php namespace Ob;
Class Start extends Controller
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        vi/setVar('title', 'Welcome to My Blog !');
        
        vi/get('view_blog', '', false);     // it includes your local view file 
    }
}
?>
```

If you visit the your site using the URL you did earlier you should see your new view. The URL was similar to this:

```php
example.com/index.php/blog/start/
```

### Adding Dynamic Data to the View <a name="adding-dynamic-data-to-the-view"></a>

------

Data is passed from the controller to the view by way of an <strong>array</strong> in the second parameter of the view loading function. Here is an example using an array:

```php
<?php

$data = array(
               'numbers' => array('1','2','3','4','5'),
               'message' => 'My Message'
          );

vi/get('view_blog', $data, false);

?>
```

Let's try it with your controller file. Open it add this code:

```php
<?php namespace Ob;
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
        
        vi/get('view_blog', $data, false);
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

### Creating Loops <a name="creating-loops"></a>

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
vi/views('myfile', '', false);
```

For more functions look at View Helper file.