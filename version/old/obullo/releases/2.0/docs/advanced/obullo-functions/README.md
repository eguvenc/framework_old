## Obullo Functions <a name="obullo-functions"></a>

Framework uses lots of functions for its operation that are <b>globally defined</b>, and are available for you at any point. These do not require loading any libraries or helpers. Enjoy it !

### General Functions

#### getConfig($config_filename, $variable = '')

getConfig is a pretty function to get configuration variables from <kbd>app/config</kbd> folder.

```php
$config = getConfig();
print_r($config); 
  
/* output 
Array ( 
[display_errors] => 1 
[timezone_set] => Europe/Istanbul 
[base_url] => http://myproject/ ... 
*/
```

In your config file, if config variable and file name do not have the same name, you need to do this:

```php
$myconfig = getConfig('myconfig', 'filename');
print_r($myconfig);
```

**Note:** You can't grab diffrerent multiple variables in one config file via *getConfig()* function. Each file must have only one config variable where its name is the same with the file name.

## Config Subfolders

```php
$transliteration = getConfig('i18n/en_US/transliteration');
```

If component is a library it returns to instance of the component.

#### getInstance()

This function returns the <b>Controller</b> object. Normally from within your controller functions you will call any of the available framework functions using the <b>$this</b> variable. <b>$this</b>, however, only works directly within your controllers, models but <b>not</b> your libraries. If you would like to use Obullo's classes within your own custom classes do so as follows:

First, assign the <b>Controller</b> object to a variable:

```php
$obullo = getInstance();
```

Once you've assigned the object to a variable, you'll use that variable instead of <b>$this</b>:

```php
$obullo = getInstance();
```

```php
$obullo->config->getItem('log_threshold');
```

#### packageExists('name');

If package installed it returns to <b>true</b> otherwise <b>false</b>.


### Logging

------

#### $this->logger->level('message')

This function lets you write messages to your log files. You must supply one of three "levels" in the first parameter, indicating what type of message it is (debug, error, info), with the message itself in the second parameter. Example:

```php
if (empty($someVar))
{
    $this->logger->error('Some variable did not contain a value.');
}
else
{
    $this->logger->error('debug', 'Some variable was correctly set');
}

$this->logger->info('The purpose of some variable is to provide some value.');


**Note:** In order for the log file to actually be written, the <b>"logs"</b> folder must be writable which is located at <kbd>app/logs</kbd>. In addition, you must set the "threshold" for logging. You might, for example, only want error messages to be logged, and not the other two types. If you set it to zero logging will be disabled. (Look at <kbd>app/config/config.php</kbd>)

**Note:** Look at log helper for more details.

### Language Functions

------

#### hasTranslate('item');

#### translate('item');

Fetches the language item from valid translate file.

```php
echo translate('Data updated succesfully');  // Data updated succesfully.
```

<b>translate()</b> function automatically supports <b>sprintf</b> functionality so you <b>don't</b> need to use sprintf.

```php
echo translate('There are %d monkeys in the %s.',5,'tree');

// There are 5 monkeys in the tree.

```

Look at <kbd>translate</kbd> package for more details.

### Loaded Classes

------

### $this->logger->method();

### $this->config->method();

### $this->uri->method();

### $this->router->method();

### $this->translator->method();

### $this->response->method();

------

### Error Functions and Headers

#### $this->response->show404();

Generates <b>404 Page Not Found</b> errors.

#### $this->response->showError($message, $status_code = 500, $heading = 'An Error Was Encountered');

Manually shows an error to users.

#### $this->response->setHttpResponse(code, 'text');

Permits you to manually set a server status header. Example:

```php
$this->response->setHttpResponse(401);  // Sets the header as:  Unauthorized
```

[See here](http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html) for a full list of headers.