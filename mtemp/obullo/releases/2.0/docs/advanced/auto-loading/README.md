## Auto-loading and Auto-running <a name="auto-loading"></a>

### Autoloader Functions

------

Obullo has a <b>autoload.php</b> functionality that is located in your <dfn>app/config</dfn> folder.

When your application start to work, if would you like to load automatically some obullo or application <samp>( models, helpers, languages, views )</samp> files for whole framework , you should define filenames to $autoload variable.

### Autoloading Helpers

------

Autoloading files loading path styles same as loader class.

```php
$autoload['helper']  = array('vi', 'html');
```

### Autoloading Libraries

------

Autoloading files loading path styles same as loader class.

```php
$autoload['library'] = array('calendar', 'myLib', );
```

### Autoloading Locale Files

------

Same as helper files.

### Autoloading Config Files

------

Same as helper files.

### Autoloading Models

------

Same as library files.

### Autorun Functions

------

Obullo has a <b>autorun.php</b> functionality that is located in your <dfn>app/config</dfn> folder.

When your application start to work, if would you like to run automatically some autoloaded <b>helper</b> functions for whole framework , you should define function names and arguments to $autorun variable.

```php
$autorun['function']['sess\start'] = array();  This configuration run the Obullo sess\start(); function.
```

You can use arguments

```php
$autorun['function']['my\function']   = array('arg1', 'arg2');
```

Above the configuration run this function <samp>my\function('arg1', 'arg2');</samp> before if you load the function helper.