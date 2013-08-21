## Common Functions <a name="common-functions"></a>

### Common Functions

------

Obullo uses a few functions for its operation that are globally defined, and are available to you at any point. These do not require loading any libraries or helpers.

#### getInstance()

This function returns the Obullo super object. Normally from within your controller functions you will call any of the available Obullo functions using the <var>$this</var> variable. <var>$this</var>, however, only works directly within your controllers, <b>not</b> models, and <b>not</b> your views. If you would like to use Obullo's classes from within your own custom classes , models or views you can do so as follows:

First, assign the Obullo object to a variable:

```php
$ob = getInstance();
```

Once you've assigned the object to a variable, you'll use that variable instead of <var>$this</var>:

```php
$ob = getInstance();

$ob->config->item('base_url');

 // or 
$ob->output->cache(60);

// etc. 
```

*Tip:* Don't forget <samp>this() = $this</samp> if <var>$this</var> variable not available in anywhere .

*Note:* For Model files <var>$this</var> variable just available for <b>database</b> operations.To using libraries inside to model you must use <samp>this()</samp> function.

#### getConfig($config_filename, $variable = '')

getConfig is a pretty function to getting configuration variables from <dfn>app/config</dfn> folder. You can use it like this

```php
$config = getConfig('config');  print_r($config); 
  
//  output
Array ( [display_errors] => 1 [timezone_set] => Europe/Istanbul [base_url] => http://localhost/obullo/ ...
```

If the config variable name in the file is not same via filename then you can grab it like this

```php
$conf = getConfig('myconfig', 'conf'); print_r($conf);
```

**Note:** You can't grab diffrerent multiple variables in one config file via *getConfig()* function. One variable must be per file.

**Tip:** If you have multiple config variables in a config file you can use associative arrays <b>$your_conf_var = array( 'setting1' => '', 'setting2' => '');</b>

First parameter is filename and the second is <b>$variable</b> name you want to fetch.

#### configItem('item_key', $filename = '')

The [Config library](/ob/config/releases/0.0.1) is the preferred way of accessing configuration information, however configItem() can be used to retrieve single keys. See Config library documentation for more information.

```php
echo configItem('base_url'); //output http://example.com  
```

If you want to get config item another folder use it like this

```php
echo configItem('html4-trans', 'doctypes'); 
```

#### dbItem('item_key', index = '')

Get db configuration items which is defined in <dfn>app/config/database.php</dfn> file.

Grab current database settings

```php
echo dbItem('hostname');   //output localhost  
```
Grab another database settings

```php
echo dbItem('hostname', 'db2');   //output localhost  
```

#### isReallyWritable('path/to/file')

isWritable() returns TRUE on Windows servers when you really can't write to the file as the OS reports to PHP as FALSE only if the read-only attribute is marked. This function determines if a file is actually writable by attempting to write to it first. Generally only recommended on platforms where this information may be unreliable.

```php
if (isReallyWritable('file.txt'))
{
    echo "I could write to this if I wanted to";
}
else
{
    echo "File is not writable";
}
```

#### setStatusHeader(code, 'text');

Permits you to manually set a server status header. Example:

```php
setStatusHeader(401);
// Sets the header as:  Unauthorized
```

[See here](http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html) for a full list of headers.
