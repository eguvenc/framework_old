## Errors & Logging <a name="error-handling-and-debugging"></a>

### Error Handling

------

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

Obullo lets you build error reporting into your applications using the functions described below. In addition, it has an error logging class that permits error and debugging messages to be saved as text files.

**Note:** By default, Obullo displays all PHP errors. You might wish to change this behavior once your development is complete. Disabling error reporting will NOT prevent log files from being written if there are errors.

### Enable / Disable Errors

------

Using your <dfn>app/config/config.php</dfn> you can control the all application errors.

```php
$config['error_reporting']       = 1;
```

this configuration will enable all errors you can use PHP ERROR CONSTANTS like this

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

Unlike most systems in Obullo, the error functions are simple procedural interfaces that are available globally throughout the application. This approach permits error messages to get triggered without having to worry about class/function scoping.

#### log\me('level', 'message')

------

This function lets you write messages to your log files. You must supply one of three "levels" in the first parameter, indicating what type of message it is (debug, error, info), with the message itself in the second parameter. Example:

```php
if ($some_var == "")
{
    log\me('error', 'Some variable did not contain a value.');
}
else
{
    log\me('debug', 'Some variable was correctly set');
}

log\me('info', 'The purpose of some variable is to provide some value.');
```

There are three message types:
<ol>
   <li>Error Messages. These are actual errors, such as PHP errors or user errors.</li>
    <li>Debug Messages. These are messages that assist in debugging. For example, if a class has been initialized, you could log this as debugging info.</li>
    <li>Informational Messages. These are the lowest priority messages, simply giving information regarding some process. Obullo doesn't natively generate any info messages but you may want to in your application.</li></ol>

**Note:** In order for the log file to actually be written, the "logs" folder must be writable which is located at <dfn>app/core/logs</dfn>. In addition, you must set the "threshold" for logging. You might, for example, only want error messages to be logged, and not the other two types. If you set it to zero logging will be disabled. (Look at <dfn>app/config/config.php</dfn>)

#### log\me('level', '[ module ]: message')

------

If you want to keep releated module log files in your current module or extension, create a <dfn>module/core/logs</dfn> folder and give the write access it, then you need to use <b>'[ module ] : '</b> string before the start of the log message. For example if you have a <b>welcome</b> module you need to use log function like this.

```php
log\me('debug', '[ welcome ]: Example message !');
```

#### Exceptions

------

We catch all exceptions with php <samp>set_exception_handler()</samp> function.You can customize exception error template which is located <dfn>app/core/errors/exception.php</dfn>.

```php
<div id="exception_content">
<b>(<?php echo $type ?>):  <?php echo error\securePath($e->getMessage(), true) ?></b><br/>
<?php 
if(isset($sql)) 
{
    echo '<span class="errorfile"><b>SQL :</b> '.$sql.'</span>';
}
?>
</div>
```

**Tip:**You can manually catch your special exceptions in try {} catch {} blocks like this...

```php
try
{
    throw new Exception('blabla');
    
} catch(Exception $e)
{
    echo $e->getMessage();  //output blabla 
}
```

### Debugging

------

Obullo lets you build user friendly debugging into your applications using the configurations described below. Open your <dfn>app/config/config.php</dfn> and look at the <var>$config['debug_backtrace']</var>

```php
$config['debug_backtrace']  = array('enabled' => 'E_ALL ^ (E_NOTICE | E_WARNING)', 'padding' => 5);
```

You can enable or disable debugging functionality or you can disable it using native php error regex strings.

```php
$config['debug_backtrace']  = array('enabled' => FALSE, 'padding' => 5)
```

If you change the padding option debugging lines will be smaller.

```php
$config['debug_backtrace']  = array('enabled' => 'E_ALL ^ (E_NOTICE | E_WARNING)', 'padding' => 3); (
```

```php
Notice): Undefined variable: undefined 
MODULES/welcome/controllers/welcome.php Code : 8 ( Line : 14 )

12     {   
13 
14         echo $undefined;
15         
16         $data['var'] = 'and generated by ';
17 
```