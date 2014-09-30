## Error Handling <a name="error-handling"></a>

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

Framework lets you build error reporting into your applications using the functions described below. In addition, it has an error logging package that permits error and debugging messages to be saved as text files.

**Note:** By default, framework displays all PHP errors. You might wish to change this behavior once your development is complete. Disabling error reporting will NOT prevent log files from being written if there are errors.

### Enable / Disable Errors

------

Using your <kbd>app/config/config.php</kbd> you can control the all application errors.

```php
$config['error_reporting']       = 1;
```

This configuration will enable all errors you can use PHP ERROR CONSTANTS like this

```php
$config['error_reporting']       = 'E_ALL';
```

This configuration will disable all errors.

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

Unlike most systems in the framework, the error functions are simple procedural interfaces that are available globally throughout the application. This approach permits error messages to get triggered without having to worry about class/function scoping.

#### Exceptions

------

We catch all exceptions with php <samp>set_exception_handler()</samp> function.You can customize exception template which is located in <kbd>app/errors/exception.php</kbd>.

```php
<div id="exception_content">
<b>(<?php echo $type ?>):  <?php echo $error->getSecurePath($e->getMessage(), true) ?></b><br/>
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