## Views <a name="views"></a>

A view is simply a web page, or a page fragment, like a header, footer, sidebar, etc. In fact, views can flexibly be embedded within other views (within other views, etc., etc.) if you need this type of hierarchy.

Views are never called directly, they must be loaded by a controller. Remember that in an MVC framework, the Controller acts as the traffic cop, so it is responsible for fetching a particular view. If you have not read the Controllers page you should do so before continuing.

**Note:** This class is initialized automatically by <b>view()</b> and <b>tpl()</b> functions so there is no need to do it manually.

**Note:** View class is a <kbd>component</kbd> defined in your package.json. You can <kbd>replace components</kbd> with third-party packages.

### Loading Views

------

To load a view file from <kbd>modules/modulename/view</kbd> folder you will use the following function:

```php
view('filename');
```

**Tip**: This function normally include a view file. If you want to load file as string use <b>false</b> parameter.

```php
echo view('filename', false);
```

### Loading Templates

------

To load a template file from <kbd>modules/templates</kbd> folder you will use the following function:

```php
tpl('filename');
```

**Tip**: This function normally include a view file. If you want to load file as string use <b>false</b> parameter.

```php
echo tpl('filename', false);
```

### Creating Variables <a name="creating-variables"></a>

------

To create view variables shown as below:

```php
view('hello_world',function() {
    $this->set('name', 'Obullo');
});
```

Getting variable values

```php
echo $name // gives Obullo
```

### Using Schemes

Scheme functions help you to write less code using php anonymous functions.
To use a scheme before you need to add it your scheme file in <b>app/config/scheme.php</b>.

```php
$scheme['general'] = function($filename){

    $this->set('header', tpl('header',false));
    $this->set('content',view($filename, false));
    $this->set('footer', tpl('footer',false));

};
```

Add <kbd>default.php</kbd> scheme file to your <kbd>modules/templates</kbd> folder.

```php
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?php echo $title ?></title>
    </head>
    <body>
        <section>
            <p><?php echo $header ?></p>
        </section>

        <?php echo $content ?>

        <section>
            <?php echo $footer ?>
        </section>
    </body>
</html>
```

Then in your controller file you can call your scheme using tpl() function.

```php
tpl('default',function() {

    $this->set('title', 'Hello World !');
    $this->scheme('general', 'hello_world');

});
```

The <b>hello_world</b> view file should be located in your <kbd>modules/welcome/view</kbd> folder.


### Adding Array Data to the View

------

Data is passed from the controller to the view by way of an <strong>array</strong> in the second parameter of the view loading function. Here is an example using an array:

```php
<?php

view('blog', function(){

        $data = array(
                       'numbers' => array('1','2','3','4','5'),
                       'message' => 'My Message'
                  );

        $this->set('mydata', $data);
});

?>
```

### Reaching Global Data

You pass global data using <b>use()</b> function.

```php
<?php

$data = array(
               'numbers' => array('1','2','3','4','5'),
               'message' => 'My Message'
                  );

$anotherData = array(
                  'title' => 'Hello World !';
                );

view('blog', function() use($data, $anotherData) {

        $this->set('mydata', $data);
        $this->set('mydata', $anotherData);
});

?>
```

Let's try it with your controller file. Open it add this code:

```php
<?php
Class Start extends Controller
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {   
        view('blog', function(){

            $data = array(
                           'numbers' => array('1','2','3','4','5'),
                           'message' => 'My Message'
                      );

            $this->set('title', 'Hello World !');
            $this->set('data', $data);
            
        });
    }
} 
?>
```

Now open your blog.php view file and change the text to variables that correspond to the array keys in your data:

```php
<h1><?php echo $title ?></h1>

Numbers:  <?php print_r($data['numbers']); ?>
Message:  <?php echo $data['message']; ?>
```

Then load the page at the URL you've been using and you should see the variables replaced.

### Creating Loops

------

The data array you pass to your view files is not limited to simple variables. You can pass multi dimensional arrays, which can be looped to generate multiple rows. For example, if you pull data from your database it will typically be in the form of a multi-dimensional array.

Now open your local view file and create a loop:

```php
<h1><?php echo $title ?></h1>

Numbers:

<ul>
<?php foreach($numbers as $item):?>

    <li><?php echo $item;?></li>

<?php endforeach;?>
</ul>

Message:  <?php echo $message; ?>
```
### Get view as string or load it as include

------

There is a second optional parameter lets you change the behavior of the function so that it load file as include instead of return to string. This can be useful if you want to process the data in some way. If you set the parameter to false (boolean) it will load file as string.

### View as String

```php
echo view('myfile', false);  
```
### View as Include

```php
view('myfile');  // default behaviour
```

### Templates

------

```php
echo tpl('header', false);
echo tpl('footer', false);
```

### Subfolders

------

You can create unlimited subfolders.

```php
echo view('subfolder/sub/filename');
```

### Function Reference

------

#### view('filename', $include = true, $data = array());

Gets the file from local directory e.g. <kbd>/modules/welcome/view</kbd>

#### tpl('filename', $include = true, $data = array());

Gets the file from templates directory e.g. <kbd>/modules/templates</kbd>

#### $this->set('key', $val = '');

Sets a view variable ( Variable types can be String, Array or Object ), this method <kbd>automatically detects</kbd> the variable types.

#### $this->scheme('scheme_key', $filename = '');

Uses scheme function that is defined in your <kbd>app/config/scheme.php</kbd>.