## Input Helper

The Input Helper serves two purposes:

<ol>
    <li>It pre-processes global input data for security.</li>
    <li>It provides some helper functions for fetching input data and pre-processing it.</li>
</ol>

**Note:** This helper is initialized automatically by the system so there is no need to do it manually.

### Using POST, COOKIE, or SERVER Data

------

Obullo comes with three helper functions that let you fetch POST, COOKIE or SERVER items. The main advantage of using the provided functions rather than fetching an item directly ($_POST['something']) is that the functions will check to see if the item is set and return false (boolean) if not. This lets you conveniently use data without having to test whether an item exists first. In other words, normally you might do something like this:

```php
if ( ! isset($_POST['something']))
{
    $something = FALSE;
}
else
{
    $something = $_POST['something'];
}
```

With Obullo's built in functions you can simply do this:

```php
$something = i\post('something');
```

The five functions are:

<ul>
    <li>i\post() ($_POST)</li>
    <li>i\get() ($_GET)</li>
    <li>i\getPost() </li>
    <li>i\request() ($_REQUEST)</li>
    <li>i\cookie ($_COOKIE)</li>
</ol>

#### i\post()

------

The first parameter will contain the name of the POST item you are looking for:

```php
i\post('some_data');
```

The function returns FALSE (boolean) if the item you are attempting to retrieve does not exist.

The second optional parameter lets you run the data through the XSS filter. It's enabled by setting the second parameter to boolean TRUE;

```php
i\post('some_data', TRUE);
```

The third optional parameter if true, function grab the original global $_POST variable instead of HMVC's $_POST variable if hmvc used.

```php
i\post('some_data', TRUE, $use_global_var = FALSE);
```

#### i\get()

This function is identical to the post function, only it fetches get data:

```php
i\get('some_data', TRUE);
```

The third optional parameter if true, function grab the original global $_GET variable instead of HMVC's $_GET variable if hmvc used.

```php
i\get('some_data', TRUE, $use_global_var = FALSE);
```

#### i\getPost()

This function will search through both the post and get streams for data, looking first in post, and then in get:

```php
i\getPost('some_data', TRUE);
```
The third optional parameter if true, function grab the original global $_GET/$_POST variable instead of HMVC's $_GET/$_POST variable if hmvc used.

```php
i\getPost('some_data', TRUE, $use_global_var = FALSE);
```

#### i\request()

This function will search through the request stream for data.

```php
i\request('some_data', TRUE);
```

The third optional parameter if true, function grab the original global $_REQUEST variable instead of HMVC's $_REQUEST variable if hmvc used.

```php
i\request('some_data', TRUE, $use_global_var = FALSE);
```

#### i\cookie()

This function is identical to the post function, only it fetches cookie data:

```php
i\cookie('some_data', TRUE);
```


#### i\ip()

Returns the IP address for the current user. If the IP address is not valid, the function will return an IP of: 0.0.0.0

```php
echo i\ip();
```

#### i\validIp($ip)

Takes an IP address as input and returns TRUE or FALSE (boolean) if it is valid or not. Note: The i_ip_address() function above validates the IP automatically.

```php
if ( ! i\validIp($ip))
{
     echo 'Not Valid';
}
else
{
     echo 'Valid';
}
```

#### i\userAgent()

Returns the user agent (web browser) being used by the current user. Returns FALSE if it's not available.

```php
echo i\userAgent();
```

#### i\ajax()

This function check is request ajax or not, If its ajax it returns TRUE otherwise FALSE.

#### i\cli()

This function check is request CLI or not, If its CLI it returns TRUE otherwise FALSE.

#### i\task()

This function check is request TASK or not, If its TASK it returns TRUE otherwise FALSE.

#### i\hmvc()

This function check is request HMVC or not, If its HMVC it returns TRUE otherwise FALSE.