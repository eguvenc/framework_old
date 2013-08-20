## Html Helper

The Head Tag Helper file contains functions that assist in working with HTML tags.

<ul>
    <li><a href="#doctype">doctype()</a></li>
    <li><a href="#css">css()</a></li>
    <li><a href="#js">js()</a></li>
    <li><a href="#subfolders">Subfolders</a></li>
    <li><a href="#modules">Modules</a></li>
    <li><a href="#plugin">plugin()</a></li>
    <li><a href="#link-tag">link_tag()</a></li>
    <li><a href="#meta">meta()</a></li>
    <li><a href="#br">br()</a></li>
    <li><a href="#heading">heading()</a></li>
    <li><a href="#img">img()</a></li>
    <li><a href="#doctype">link_tag()</a></li>
    <li><a href="#nbs">nbs()</a></li>
     <li><a href="#ul-and-ol">ol() and ul()</a></li>
</ul>

### Loading this Helper

------

This helper is loaded using the following code:

```php
new html\start();
```

The following functions are available:

#### doctype($doctype = 'xhtml1-strict') <a name="doctype"></a>

Helps you generate document type declarations, or DTD's. XHTML 1.0 Strict is used by default, but many doctypes are available.

```php
echo doctype();
// <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

echo doctype('html4-trans');
// <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
```

The following is a list of doctype choices. These are configurable, and pulled from app/config/doctypes.php


<table><thead><tr>

<th>Doctype</th><th>Option</th><th>Result</th></thead><tbody>
<tr><td>XHTML 1.1</td><td>doctype('xhtml11')</td><td><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"></td></tr>
<tr><td>XHTML 1.0 Strict</td><td>doctype('xhtml1-strict')</td><td></td></tr> 	
<tr><td>XHTML 1.0 Transitional</td><td>doctype('xhtml1-trans')</td><td><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"></td></tr>
<tr><td>XHTML 1.0 Frameset</td><td>doctype('xhtml1-frame')</td><td><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd"></td></tr>
<tr><td>HTML 5</td><td>doctype('html5')</td><td><!DOCTYPE html></td></tr>
<tr><td>HTML 4 Strict</td><td>doctype('html4-strict')</td><td><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"></td></tr>
<tr><td>HTML 4 Transitional</td><td>doctype('html4-trans')</td><td><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"></td></tr>
<tr><td>HTML 4 Frameset</td><td>doctype('html4-frame')</td><td><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd"></td></tr></tbody></table>

#### css($filename, $title_or_embed = '', $media = '', $rel = 'stylesheet', $index_page = FALSE)<a name="css"></a>

Load.css files from <dfn>public/css</dfn> folder. Example:

```php
echo css('welcome.css');

// output
<link href="/public/css/welcome.css" rel="stylesheet" type="text/css" />
```

Additionally, an associative array can be passed to the css() function for complete control over all attributes and values.

```php
$css = array(
          'href' => 'css/welcome.css',
          'rel' => 'stylesheet',
          'type' => 'text/css',
          'media' => 'print'
);
echo css($css);

// output
<link href="/public/css/welcome.css" rel="stylesheet" type="text/css"  media="print" />
```

#### js($filename, $arguments = '', $type = 'text/javascript')<a name="js"></a>

Load .js files from public/js folder. Example:

```php
echo js('welcome.js');

// output
<script type="text/javascript" src="/public/js/welcome.js"></script>
```

Additionally, an associative array can be passed to the css() function for complete control over all attributes and values.

```php
$js = array(
          'src' => 'welcome.js',
          'type' => 'text/javascript',  
);
echo js($js);
// output
<script  src="/public/js/welcome.js" type="text/javascript" ></script>
```

#### Loading From Subfolders<a name="subfolders"></a>

You can change the paths simply or you can provide sub paths.

```php
echo css('js/subfolder/subfolder/welcome.css');

// <link  href="/modules/welcome/public/js/subfolder/subfolder/welcome.css" rel="stylesheet" type="text/css" />
```

#### Loading From Modules<a name="modules"></a>

You can load some html helper functions from another modules.

```php
echo css('../modulename/module.css');
echo js('../modulename/module.js');
echo img('../modulename/my_image.jpg');

// <link  href="/modules/modulename/public/css/module.css" rel="stylesheet" type="text/css" />
```

#### Loading From Sub Modules

You can load some html helper functions from sub modules.

```php
echo css('sub.backend/welcome/test.css');
// <link  href="/modules/sub.backend/modules/welcome/public/css/test.css" rel="stylesheet" type="text/css" />
```

#### plugin(plugin_name, $filename = 'plugins')<a name="plugin"></a>

The plugin file which is located your <samp>app/config/plugins.php or modules/modulename/config/plugins.php</samp> contains arrays of your "Js" or "Css" type files. It is used by the View helper functions to help set heag tag variables js, css data.The array keys are used to identify the plugin name and the array values are used to set the actual filename and path of the public items.

Loading application plugins

```php
view_var('head', plugin('form', 'app/plugins'));
```

Loading modules plugins

```php
view_var('head', plugin('form'));
```

#### Loading sub module plugins

```php
view_var('head', plugin('form', 'sub.backend/plugins');
```

#### link_tag() <a name="link"></a>

Lets you create HTML tags.The parameters are href, with optional rel, type, title, media and index_page. index_page is a TRUE/FALSE value that specifics if the href should have the page specified by $config['index_page'] added to the address it creates.

Examples:

```php
echo link_tag('favicon.ico', 'shortcut icon', 'image/ico');

// <link href="/favicon.ico" rel="shortcut icon" type="image/ico" />

echo link_tag('feed', 'alternate', 'application/rss+xml', 'My RSS Feed');

// <link href="/feed" rel="alternate" type="application/rss+xml" title="My RSS Feed" />
```

**Tip:** We don't use link_tag() function for loading stylesheets files beacuse of we have a portable function called css.

#### meta() <a name="meta"></a>

Helps you generate meta tags. You can pass strings to the function, or simple arrays, or multidimensional ones. Examples:

```php
echo meta('description', 'My Great site');

// Generates: <meta name="description" content="My Great Site" />


echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); 

// Generates: <meta http-equiv="Content-type" content="text/html; charset=utf-8" />


echo meta(array('name' => 'robots', 'content' => 'no-cache'));

// Generates: <meta name="robots" content="no-cache" />


$meta = array(
        array('name' => 'robots', 'content' => 'no-cache'),
        array('name' => 'description', 'content' => 'My Great Site'),
        array('name' => 'keywords', 'content' => 'love, passion, intrigue, deception'),
        array('name' => 'robots', 'content' => 'no-cache'),
        array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv')
    );

echo meta($meta);

// Generates:
```

The Body Tags.

The following functions are available:

#### br() <a name="br"></a>

Generates line break tags based on the number you submit. Example:

```php
echo br(3);
```

The above would produce: 
```php
<br /><br /><br />
```

#### heading() <a name="heading"></a>

Lets you create HTML<b>h1</b> tags. The first parameter will contain the data, the second the size of the heading. Example:

```php
echo heading('Welcome!', 3);
```

The above would produce: 

```php
<h3>Welcome!</h3>
```

#### img() <a name="img"></a>

Lets you create HTML <b>img</b> tags. The first parameter contains the image source. Example:

```php
echo img('images/picture.jpg');

// gives <img src="http://site.com/public/images/picture.jpg" />
```

There is an optional second parameter that is a TRUE/FALSE value that specifics if the src should have the page specified by $config['index_page'] added to the address it creates. Presumably, this would be if you were using a media controller.

```php
echo img('images/picture.jpg', TRUE);

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

echo img($image_properties);

// Produces:
```

#### nbs() <a name="nbs"></a>

Generates non-breaking spaces ( ) based on the number you submit. Example:

```php
echo nbs(3);
```

#### ol() and ul() <a name="ul-and-ol"></a>

Permits you to generate ordered or unordered HTML lists from simple or multi-dimensional arrays. 
Example:
$list = array( 'red', 'blue', 'green', 'yellow' ); $attributes = array( 'class' => 'boldlist', 'id' => 'mylist' ); echo ul($list, $attributes);

The above code will produce this:

```php
<ul class="boldlist" id="mylist">
  <li>red</li>
  <li>blue</li>
  <li>green</li>
  <li>yellow</li>
</ul>
```

Here is a more complex example, using a multi-dimensional array:

```php
$attributes = array(
                    'class' => 'boldlist',
                    'id'    => 'mylist'
                    );

$list = array(
            'colors' => array(
                                'red',
                                'blue',
                                'green'
                            ),
            'shapes' => array(
                                'round',
                                'square',
                                'circles' => array(
                                                    'ellipse',
                                                    'oval',
                                                    'sphere'
                                                    )
                            ),
            'moods'    => array(
                                'happy',
                                'upset' => array(
                                                    'defeated' => array(
                                                                        'dejected',
                                                                        'disheartened',
                                                                        'depressed'
                                                                        ),
                                                    'annoyed',
                                                    'cross',
                                                    'angry'
                                                )
                            )
            );


echo ul($list, $attributes);
```

The above code will produce this:

```php
<ul class="boldlist" id="mylist">
  <li>colors
    <ul>
      <li>red</li>
      <li>blue</li>
      <li>green</li>
    </ul>
  </li>
  <li>shapes
    <ul>
      <li>round</li>
      <li>suare</li>
      <li>circles
        <ul>
          <li>elipse</li>
          <li>oval</li>
          <li>sphere</li>
        </ul>
      </li>
    </ul>
  </li>
  <li>moods
    <ul>
      <li>happy</li>
      <li>upset
        <ul>
          <li>defeated
            <ul>
              <li>dejected</li>
              <li>disheartened</li>
              <li>depressed</li>
            </ul>
          </li>
          <li>annoyed</li>
          <li>cross</li>
          <li>angry</li>
        </ul>
      </li>
    </ul>
  </li>
</ul> 
```