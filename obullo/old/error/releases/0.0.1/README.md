## Error Handling

Error Codes and Error Constants

```php
http://usphp.com/manual/en/errorfunc.constants.php
 
1     E_ERROR
2     E_WARNING
4     E_PARSE
8     E_NOTICE
16    E_CORE_ERROR
32    E_CORE_WARNING
64    E_COMPILE_ERROR
128   E_COMPILE_WARNING
256   E_USER_ERROR
512   E_USER_WARNING
1024  E_USER_NOTICE
2048  E_STRICT
4096  E_RECOVERABLE_ERROR
8192  E_DEPRECATED
16384 E_USER_DEPRECATED
30719 E_ALL
```

Framework lets you build error reporting into your applications using the functions described below. In addition, it has an error logging class that permits error and debugging messages to be saved as text files.

**Note:** By default, Framework displays all PHP errors. You might wish to change this behavior once your development is complete. Disabling error reporting will NOT prevent log files from being written if there are errors.

### Enable / Disable Errors

------

In your <dfn>app/config/config.php</dfn> you can control the all application errors.

```php
$config['error_reporting']       = 1;
```

this configuration will enable all errors and you can use PHP ERROR CONSTANTS like this

```php
$config['error_reporting']       = 'E_ALL';
```

this configuration will disable all errors.

```php
$config['error_reporting']       = 0;
```

You can do more using php error constants. Look at below the examples.

```php
|   String - Custom Regex Mode Examples:
|
|   Running errors
|       $config['error_reporting'] = 'E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR';
|   
|   Running errors + notices
|       $config['error_reporting'] = 'E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_NOTICE';
|   
|   All errors except notices, warnings, exceptions and database errors
|       $config['error_reporting'] = 'E_ALL ^ (E_NOTICE | E_WARNING | E_EXCEPTION | E_DATABASE)';
|       
|   All errors except notices 
|       $config['error_reporting'] = 'E_ALL ^ E_NOTICE';
```

The following functions let you generate errors:

#### $this->response->showError('message' [, int $status_code= 500 ] )

This function will display the error message supplied to it using the following error template:

You can <b>customize</b> this template which is located at <dfn>app/errors/general.php</dfn>

The optional parameter <dfn>$status_code</dfn> determines what HTTP status code should be sent with the error.

```php
$this->response->showError('There is an error occured');
```

This function will display the 404 error message supplied to it using the following error template:

```php
$this->response->show404('page')
```

You can <b>customize</b> this template which is located at <dfn>app/errors/</dfn><kbd>404.php</kbd>

The function expects the string passed to it to be the file path to the page that isn't found. Note that framework automatically shows 404 messages if controllers are not found.

#### $this->logger->level($message);

This function lets you write messages to your log files. You must supply one of three "levels" in the first parameter, indicating what type of message it is (debug, error, info), with the message itself in the second parameter. Example:

```php
if ($some_var == '')
{
    $this->logger->error('Some variable did not contain a value.');
}
else
{
    $this->logger->debug('Some variable was correctly set');
}

$this->logger->info('The purpose of some variable is to provide some value.');
```

There are three message types:
<ol>
<li>Error Messages. These are actual errors, such as PHP errors or user errors.</li>
<li>Debug Messages. These are messages that assist in debugging. For example, if a class has been initialized, you could log this as debugging info.</li>
<li>Informational Messages. These are the lowest priority messages, simply giving information regarding some process. Obullo doesn't natively generate any info messages but you may use in your application.</li></ol>

**Note:** In order for the log file to actually be written, the "logs" folder must be writable which is located at <dfn>app/logs</dfn>. In addition, you must set the "threshold" for logging. You might, for example, only want error messages to be logged, and not the other two types. If you set it to zero logging will be disabled. (Look at <dfn>app/config/config.php</dfn>)

### Exceptions

------

We catch all exceptions with php <dfn>set_exception_handler()</dfn> function. Exception file located <dfn>packages/obullo/src/exception.php</dfn>.

```php
<style type="text/css">
#ErrorContent  {
font-family: verdana;
font-size:12;e
width:50%;
padding:5px;
background-color:#eee;
}
</style>

<div id="ErrorContent">

<b>[<?php echo $type; ?> Error]: </b> <?php echo $e->getMessage(); ?> <br />
<?php if($e->getCode() != 0)  { ?>

<b>Code:</b> <?php echo $e->getCode(); ?> <br />

<?php } ?>
<b>File:</b> <?php echo $e->getFile(); ?> <br />
<b>Line:</b> <?php echo $e->getLine(); ?>

<?php if($sql != '') { ?>

<br /><b>SQL: </b> <?php echo $sql; ?>

<?php } ?>
    
</div>
```

**Tip:**You can manually catch your special exceptions in try {} catch {} blocks like this...

```php
try
{
    throw new Exception('blabla');
    
} catch(Exception $e)
{
    echo $e->getMessage();  // output blabla 
}
```

### Debugging

------

Framework lets you build user friendly debugging into your applications using the configurations described below. Open your <dfn>app/config/config.php</dfn> and look at the <var>$config['debug_backtrace']</var>.

```php
$config['debug_backtrace']  = array('enabled' => 'E_ALL ^ (E_NOTICE | E_WARNING)', 'padding' => 5);
```

You can enable or disable debugging functionality or you can disable it using native php error regex strings.

```php
$config['debug_backtrace']  = array('enabled' => false, 'padding' => 5)
```

If you change the padding option debugging lines will be smaller.

```php
$config['debug_backtrace']  = array('enabled' => 'E_ALL ^ (E_NOTICE | E_WARNING)', 'padding' => 3);
```

```php
(Notice): Undefined variable: undefined 
PUBLIC_DIR/welcome/controllers/welcome.php Code : 8 ( Line : 14 )

12     {   
13 
14         echo $undefined;
15         
16         $data['var'] = 'and generated by ';
17 
```

#### Debugging Logs from Your Console

When your application works you may want see all log files from console for good debugging. To activate this functionality firstly you need install <kbd>task</kbd> package. Then you need to run below the command.

```php
$php task log
```
You can set filter for log level

```php
$php task log level info
```

```php
$php task log level debug
```