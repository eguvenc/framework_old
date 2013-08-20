## Config Class

------

The Config class provides a means to retrieve configuration preferences. These preferences can come from the default config file (<samp>app/config/config.php</samp>) or from your own custom config files.

**Note:** This class is initialized automatically by the system so there is no need to do it manually.

### Anatomy of a Config File

------

By default, Obullo has a one primary config file, located at <samp>app/config/config.php</samp>. If you open the file using your text editor you'll see that config items are stored in an array called <var>$config</var>.

You can add your own config items to this file, or if you prefer to keep your configuration items separate (assuming you even need config items), simply create your own file and save it in <dfn>config</dfn> folder.

**Note:** If you do create your own config files use the same format as the primary one, storing your items in an array called $config. Obullo will intelligently manage these files so there will be no conflict even though the array has the same name (assuming an array index is not named the same as another).

### Loading a Config File

------

**Note:** Obullo automatically loads the primary config file (<samp>app/config/config.php<samp>), so you will only need to load a config file if you have created your own.

There are two ways to load a config file:


<ol><li> Manual Loading</li>

To load one of your custom config files you will use the following function within the [controller](/docs/general/#controllers) that needs it:

<pre>
<code>
$this->config->load('filename');
</code>
</pre>

Where <var>filename</var> is the name of your config file, without the .php file extension.

If you need to load multiple config files normally they will be merged into one master config array. Name collisions can occur, however, if you have identically named array indexes in different config files. To avoid collisions you can set the second parameter to <kbd>TRUE</kbd> and each config file will be stored in an array index corresponding to the name of the config file. Example:

<pre>
<code>
// Stored in an array with this prototype:
$this->config['blog_settings'] = $config 

$this->config->load('blog_settings', TRUE);
</code>
</pre>
Please see the section entitled <dfn>Fetching Config Items</dfn> below to learn how to retrieve config items set this way.

The third parameter allows you to suppress errors in the event that a config file does not exist:

<pre>
<code>
$this->config->load('blog_settings', FALSE, TRUE);
</code>
</pre>

<li>Auto-loading</li>

If you find that you need a particular config file globally, you can have it loaded automatically by the system. To do this, look at this section auto-load.
</ol>

### Fetching Config Items

------

To retrieve an item from your config file, use the following function:

```php
$this->config->item('item name');
```

Where <var>item name</var>is the $config array index you want to retrieve. For example, to fetch your language choice you'll do this:

```php
$lang = $this->config->item('locale');
```

The function returns FALSE (boolean) if the item you are trying to fetch does not exist.

If you are using the second parameter of the <kbd>$this->config->load();</kbd> function in order to assign your config items to a specific index you can retrieve it by specifying the index name in the second parameter of the <kbd>$this->config->item()</kbd> function. Example:

```php
// Loads a config file named blog_settings.php and assigns
 it to an index named "blog_settings"
$this->config->load('blog_settings', TRUE);

// Retrieve a config item named site_name contained within the blog_settings array
$site_name = $this->config->item('site_name', 'blog_settings');

// An alternate way to specify the same item:
$blog_config = $this->config->item('blog_settings');

$site_name = $blog_config['site_name'];
```

### Setting a Config Item

------

If you would like to dynamically set a config item or change an existing one, you can so using:

```php
$this->config->set('item_name', 'item_value');
```

Where <var>item_name</var> is the $config array index you want to change, and <var>item_value</var> is its value.

### Helper Functions

------

The config class has the following helper functions:

#### $this->config->base();

This function retrieves the URL to your site without "index" value you've specified in the config file.

#### $this->config->public();

This function retrieves the Public URL to your you've specified in the config file.

#### $this->config->siteUrl($uri = '', $suffix = true);

This function retrieves the URL to your site, along with the "index" value you've specified in the config file.If you set before url suffix (like .html) in config.php using second parameter <b>$suffix = false</b> you can switch off suffix for current site url.

#### $this->config->baseFolder();

This function retrieves the URL to your base folder.