## Html Helper

The Head Tag Helper file contains functions that assist in working with HTML tags.

<ul>
    <li><a href="#css">css()</a></li>
    <li><a href="#js">js()</a></li>
    <li><a href="#img">img()</a></li>
</ul>

### Loading this Helper

------

This helper is loaded using the following code:

```php
new html\start();
```

The following functions are available:


#### css($filename, $title_or_embed = '', $media = '', $rel = 'stylesheet', $index_page = false)<a name="css"></a>

Load.css files from <dfn>assets/css</dfn> folder. Example:

```php
echo css('welcome.css');

// output
<link href="/assets/css/welcome.css" rel="stylesheet" type="text/css" />
```

Additionally, an associative array can be passed to the css() function for complete control over all attributes and values.

```php
$css = array(
          'href' => 'css/welcome.css',
          'rel' => 'stylesheet',
          'type' => 'text/css',
          'media' => 'print'
);
echo html\css($css);

// output
<link href="/assets/css/welcome.css" rel="stylesheet" type="text/css"  media="print" />
```

#### js($filename, $arguments = '', $type = 'text/javascript')<a name="js"></a>

Load .js files from assets/js folder. Example:

```php
echo html\js('welcome.js');

// output
<script type="text/javascript" src="/assets/js/welcome.js"></script>
```

Additionally, an associative array can be passed to the css() function for complete control over all attributes and values.

```php
$js = array(
          'src' => 'welcome.js',
          'type' => 'text/javascript',  
);
echo js($js);
// output
<script  src="/assets/js/welcome.js" type="text/javascript" ></script>
```

#### Loading From Subfolders<a name="subfolders"></a>

You can change the paths simply or you can provide sub paths.

```php
echo html\css('js/subfolder/subfolder/welcome.css');

// <link  href="/modules/welcome/assets/js/subfolder/subfolder/welcome.css" rel="stylesheet" type="text/css" />
```

#### Loading From Modules<a name="modules"></a>

You can load some html helper functions from another modules.

```php
echo html\css('../modulename/module.css');
echo html\js('../modulename/module.js');
echo html\img('../modulename/my_image.jpg');

// <link  href="/modules/modulename/assets/css/module.css" rel="stylesheet" type="text/css" />
```

#### img() <a name="img"></a>

Lets you create HTML <b>img</b> tags. The first parameter contains the image source. Example:

```php
echo html\img('images/picture.jpg');

// gives <img src="http://site.com/assets/images/picture.jpg" />
```

There is an optional second parameter that is a true/false value that specifics if the src should have the page specified by $config['index_page'] added to the address it creates. Presumably, this would be if you were using a media controller.

```php
echo html\img('images/picture.jpg', true);

// gives <img src="http://site.com/index.php/images/picture.jpg" />
```

Additionally, an associative array can be passed to the img() function for complete control over all attributes and values.

```php
$image_properties = array(
      'src'    => 'images/picture.jpg',
      'alt'    => 'Me, demonstrating how to eat 4 slices of pizza at one time',
      'class'  => 'post_images',
      'width'  => '200',
      'height' => '200',
      'title'  => 'That was quite a night',
      'rel'    => 'lightbox',
);

echo html\img($image_properties);

// Produces:
```

