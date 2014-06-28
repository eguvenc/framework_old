## Html Class

The Html Helper package contains a few functions that assist in working with HTML tags.

<ul>
    <li><a href="#css">$this->html->css()</a></li>
    <li><a href="#js">$this->html->js()</a></li>
    <li><a href="#img">$this->html->img()</a></li>
</ul>

### Loading Single File

```php
echo $this->html->css('welcome.css');
echo $this->html->js('welcome.js');

// gives <link href="/assets/css/welcome.css" rel="stylesheet" type="text/css" />
// gives <script type="text/javascript" src="/assets/js/welcome.js"></script>
```

### Loading Folder

Using this <kbd>"/*"</kbd> sign you can load all grouped files in a folder.

```php
echo $this->html->js('jquery/*');

// gives <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
// gives <script type="text/javascript" src="/assets/js/jquery.livequery.js"></script>
// gives <script type="text/javascript" src="/assets/js/jquery.map.js"></script>
```

### Exclude Files

Sets excluded files if you don't want to load all contents of the folder.

```php
echo $this->html->js('jquery/*^(jquery.livequery.js)');

// gives <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
// gives <script type="text/javascript" src="/assets/js/jquery.map.js"></script>
```

### Getting Strict Files

You can get strict files if you don't want to load all contents of the folder.

```php
echo $this->html->js('jquery/*(jquery.min.js)');

// gives <script type="text/javascript" src="/assets/js/jquery.min.js"></script>

echo $this->html->js('jquery/*(jquery.min.js|jquery.map.js)');

// gives <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
// gives <script type="text/javascript" src="/assets/js/jquery.map.js"></script>
```

### css() <a name="css"></a>

```php
echo $this->html->css('welcome.css');

// gives <link href="/assets/css/welcome.css" rel="stylesheet" type="text/css" />
```

#### Additionally you can pass extra paramaters.

```php
echo $this->html->css('welcome.css', $title='' $media = 'print', $rel='stylesheet', $index_page = false);

// gives <link href="/assets/css/welcome.css"  title="" rel="stylesheet" type="text/css"  media="print" />
```

#### js()<a name="js"></a>

Loading .js files from <kbd>assets/js</kbd> folder. Example:

```php
echo $this->html->js('welcome.js');

// gives <script type="text/javascript" src="/assets/js/welcome.js"></script>
```

#### Additionally you can pass extra paramaters.

```php
echo $this->html->js('welcome.js', $arguments = '', $type = 'text/javascript', $index_page = false);
```

#### img() <a name="img"></a>

Lets you create HTML <b>img</b> tags. The first parameter contains the image source. Example:

```php
echo $this->html->img('picture.jpg');

// gives <img src="http://site.com/assets/images/picture.jpg" />
```
There is an optional second parameter that is a true/false value that specifies if the src should have the page specified by $config['index_page'] added to the address it creates. Presumably, this would be if you were using a media controller.

```php
echo $this->html->img('picture.jpg', true);

// gives <img src="http://site.com/index.php/images/picture.jpg" />
```

Additionally, an associative array can be passed to the img() function for complete control over all attributes and values.

```php
$imageProperties = array(
      'src'    => 'picture.jpg',
      'alt'    => 'Me, demonstrating how to eat 4 slices of pizza at one time',
      'class'  => 'postImages',
      'width'  => '200',
      'height' => '200',
      'title'  => 'That was quite a night',
      'rel'    => 'lightbox',
);

echo $this->html->img($imageProperties);

// gives <img src="/assets/images/picture.jpg" alt="Me, demonstrating how to eat 4 slices of pizza at one time"  class="postImages" width="200" height="200" title="That was quite a night" rel="lightbox" />
```

#### Subfolders and Paths

You can change the paths simply or you can provide sub paths.

```php
echo $this->html->css('js/subfolder/example.css');

// gives <link  href="/modules/assets/js/subfolder/example.css" rel="stylesheet" type="text/css" />
```

### Function Reference

------

#### $this->html->css($filename, $title = '', $media = '', $rel = 'stylesheet', $index_page = false)

Loads .css files from <kbd>assets/css</kbd> folder.

#### $this->html->js($src, $arguments = '', $type = 'text/javascript', $index_page = false)

Loads .js files from <kbd>assets/js</kbd> folder.

#### $this->html->img($src = '', $attributes = '', $index_page = false)

Loads image files from <kbd>assets/images</kbd> folder.