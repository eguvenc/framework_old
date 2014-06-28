
## Views Class

A view is simply a web page, or a page fragment, like a header, footer, sidebar, etc. In fact, views can flexibly be embedded within other views (within other views, etc., etc.) if you need this type of hierarchy.

Views are never called directly, they must be loaded by a controller. Remember that in a MVC framework, the Controller acts as the traffic cop, so it is responsible for fetching a particular view. If you have not read the Controllers page you should do so before continuing.

### Initializing the Class

------

```php
new View;
$this->view->method();
```
Once loaded, the FTP object will be available using: <dfn>$this->view->method()</dfn>


### Loading Views

------

To load a view file from <kbd>modules/modulename/view</kbd> folder you will use the following function:

```php
$this->view->get('filename');
```

**Tip**: This function normally include a view file. If you want to load file as string use <b>false</b> parameter.

```php
echo $this->view->get('filename', false);
```

### Loading Templates

------

To load a template file from <kbd>modules/templates</kbd> folder you will use the following function:

```php
$this->view->tpl('filename');
```

**Tip**: This function normally include a view file. If you want to load file as string use <b>false</b> parameter.

```php
echo $this->view->tpl('filename', false);
```

### Creating Variables <a name="creating-variables"></a>

------

To create view variables shown as below:

```php
$this->view->get('hello_world',function() {
    $this->set('name', 'Obullo');
});
```

Getting variable values

```php
echo $name // gives Obullo
```

### Using Schemes

Scheme functions help you to write less code using php anonymous functions.
To using a scheme before you need to add it your scheme file in <b>app/config/scheme.php</b>.

```php
$scheme['general'] = function($filename){

    $this->set('header', $this->tpl('header',false));
    $this->set('content',$this->get($filename, false));
    $this->set('footer', $this->tpl('footer',false));

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
$this->view->get('',function() {

    $this->set('title', 'Hello World !');
    $this->getScheme('yourfilename', 'general')

});
```

The <b>hello_world</b> view file should be located in your <kbd>modules/welcome/view</kbd> folder.


### Adding Array Data to the View

------

Data is passed from the controller to the view by an <strong>array</strong> in the second parameter of the view loading function. Here is an example using an array:

```php
<?php

$this->view->get('blog', function(){

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

$this->view->get('blog', function() use($data, $anotherData) {

        $this->set('mydata', $data);
        $this->set('mydata', $anotherData);
});

?>
```

Let's try it with your controller file. Open it add this code:

```php
<?php

/**
 * $c hello_world
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
});

$c->func('index', function() use($c){
  
    $data = array(
                   'numbers' => array('1','2','3','4','5'),
                   'message' => 'My Message'
              );

    $this->view->get('blog', function() use($data){

        $this->set('title', 'Hello World !');
        $this->set('data', $data);
    });
    
});   
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

There is a second optional parameter that lets you change the behavior of the function so that it loads file as include instead of return to string. This can be useful if you want to process the data in some way. If you set the parameter to false (boolean) it will load file as string.

### View as String

```php
echo $this->view->get('myfile', false);  
```
### View as Include

```php
$this->view->get('myfile');  // default behaviour
```

### Templates

------

```php
echo $this->view->tpl('header', false);
echo $this->view->tpl('footer', false);
```

### Subfolders

------

You can create unlimited subfolders.

```php
echo $this->view->get('subfolder/sub/filename');
```

### Function Reference

------

#### $this->view->get('filename', $include = true, $data = array());

Gets the file from local directory e.g. <kbd>/modules/welcome/view</kbd>

#### $this->view->tpl('filename', $include = true, $data = array());

Gets the file from templates directory e.g. <kbd>/modules/templates</kbd>

#### $this->view->set('key', $val = '');

Sets a view variable ( Variable types can be String, Array or Object ), this method <kbd>automatically detects</kbd> the variable types.

#### $this->view->getScheme('scheme_key');

Uses the scheme function that is defined in your <kbd>app/config/scheme.php</kbd>.