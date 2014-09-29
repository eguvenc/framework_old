## Get Class ( Get The Php Super Globals )

The Get Class serves two purposes:

<ol>
    <li>It pre-processes global input data for security.</li>
    <li>It provides some helper functions for fetching input data and pre-processing it.</li>
</ol>

### Initializing the Class

------

```php
new Get;
$this->get->method();
```
Once loaded, the Get object will be available using: <dfn>$this->get->method()</dfn>


### Using POST, GET, REQUEST or SERVER Data

------

"i" comes with input helper functions that let you fetch POST, GET, COOKIE or SERVER items. The main advantage of using the provided functions rather than fetching an item directly ($_POST['something']) is that the functions will check to see if the item is set and return false (boolean) if not. This lets you conveniently use data without having to test whether an item exists first. In other words, normally you might do something like this:

```php
if ( ! isset($_POST['something']))
{
    $something = false;
}
else
{
    $something = $_POST['something'];
}
```

With "Get" class built in functions you can simply do this:

```php
new Get;

$something = $this->get->post('something');
```

Some popular "Get" helper functions:

* $this->get->post() ($_POST)
* $this->get->get() ($_GET)
* $this->get->request() ($_REQUEST)
* $this->get->server() ($_SERVER)

#### $this->get->post()

Post method first check the $_POST data if key not exists it returns false. If key exists it check $_GET data.

The first parameter will contain the name of the POST item you are looking for:

```php
$this->get->post('some_data');
```

The function returns false (boolean) if the item you are attempting to retrieve does not exist.

The second optional parameter lets you run the data through the XSS filter. It's enabled by setting the second parameter to boolean true;

```php
$this->get->post('some_data', true);
```

If the third optional parameter is true, function grabs the original global $_POST variable instead of HVC's (if hvc used).

```php
$this->get->post('some_data', true, $use_global_var = false);
```

#### $this->get->get()

This function is identical to the post function, only it fetches the get data:

```php
$this->get->get('some_data', true);
```

If the third optional parameter is true, function grabs the original global $_GET variable instead of HVC's (if hvc used).

```php
$this->get->get('some_data', true, $use_global_var = false);
```

#### $this->get->server()

Returns false if $SERVER data is not available.

```php
echo $this->get->server('HTTP_USER_AGENT');
```

#### $this->get->request()

This function will search through the request stream for the data.

```php
$this->get->request('some_data', true);
```

If the third optional parameter is true, function grabs the original global $_REQUEST variable instead of HVC's (if hvc used).

```php
$this->get->request('some_data', true, $use_global_var = false);
```