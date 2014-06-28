## Config Class

The Config class provides a means to retrieve configuration preferences. These preferences can come from the default config file <kbd>app/config/config.php</kbd> or from your own custom config files.

**Note:** This class is initialized automatically by the system so there is no need to do it manually.

**Note:** This class is a <kbd>component</kbd> defined in your package.json. You can <kbd>replace components</kbd> with third-party packages.

### Anatomy of a Config File

------

By default, Framework has one primary config file, located at <kbd>app/config/config.php</kbd>. If you open the file using your text editor you'll see that config items are stored in an array called <var>$config</var>.

You can add your own config items to this file, or if you prefer to keep your configuration items separated (assuming you even need config items), simply create your own file and save it in <dfn>config</dfn> folder.

**Note:** If you do create your own config files use the same format as the primary one, storing your items in an array called $config. Obullo will intelligently manage these files so there will be no conflict even though the array has the same name ( assuming an array index is not named the same as another ).

### Loading a Config File

------

**Note:** Framework automatically loads the primary config file <kbd>app/config/config.php</kbd>, so you will only need to load a config file if you have created your own.

There are two ways to load a config file:


<ol><li> Manual Loading</li>

To load one of your custom config files you will use the following function within the <samp>controller</samp> that needs it:

<pre>
<code>
$this->config->load('filename');
</code>
</pre>

Where <var>filename</var> is the name of your config file, without the .php file extension.

If you need to load multiple config files normally they will be merged into one master config array. Name collisions can occur, however, if you have identically named array indexes in different config files. To avoid collisions you can set the second parameter to <kbd>true</kbd> and each config file will be stored in an array index corresponding to the name of the config file. Example:

<pre>
<code>
$this->config->load('settings', true);
</code>
</pre>
Please see the section entitled <dfn>Getting Config Items</dfn> below to learn how to retrieve config items set this way.

<li>Auto-loading</li>

If you find that you need a particular config file globally, you can have it loaded automatically by the system. To do this, look at this section auto-load.
</ol>

### Getting Config Items

------

To retrieve an item from your config file, use the following function:

```php
$this->config->getItem('itemname');
```

Where <var>itemname</var> is the <dfn>$config<dfn> array index you want to retrieve. For example, to fetch your language choice you'll do this:

```php
$lang = $this->config->getItem('default_translations');
```

The function returns false (boolean) if the item you are trying to fetch does not exist.

If you are using the second parameter of the <kbd>$this->config->load();</kbd> function in order to assign your config items to a specific index you can retrieve it by specifying the index name in the second parameter of the <kbd>$this->config->getItem()</kbd> function. Example:

```php
// Loads a config file named settings.php and assigns it to an index named "settings"
$this->config->load('settings');

// Retrieve a config item named site_name contained within the settings array
$siteName = $this->config->getItem('sitename', 'settings');

// An alternative way to specify the same item:
$config = $this->config->getItem('settings');

$siteName = $config['sitename'];
```

### Setting a Config Item

------

If you would like to dynamically set a config item or change an existing one, you can do using:

```php
$this->config->setItem('item', 'value');
```

Where <var>item</var> is the $config array index you want to change, and <var>value</var> is its value.