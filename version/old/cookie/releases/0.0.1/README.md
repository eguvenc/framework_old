## Cookie Class

------

The Cookie Class contains functions that assist in working with cookies.

### Initializing the Class

-------

```php
new Cookie();
$this->cookie->method();
```

Once loaded, the Cookie object will be available using: <dfn>$this->cookie->method()</dfn>

The following functions are available:

#### $this->cookie->set()

Sets a cookie containing the values you specify. There are two ways to pass information to this function so that a cookie can be set: Array Method, and Discrete Parameters:

<b>Array Method</b>

Using this method, an associative array is passed to the first parameter:

```php
$cookie = array(
                   'name'   => 'The Cookie Name',
                   'value'  => 'The Value',
                   'expire' => '86500',
                   'domain' => '.some-domain.com',
                   'path'   => '/',
                   'prefix' => 'myprefix_',
               );

$this->cookie->set($cookie); 
```

<b>Notes:</b>

Only the name and value are required. To delete a cookie replace it with the expiration blank.

The expiration is set in <b>seconds</b>, which will be added to the current time. Do not include the time, but rather only the number of seconds from now that you wish the cookie to be valid. If the expiration is set to zero the cookie will only last as long as the browser is open.

For site-wide cookies regardless of how your site is requested, add your URL to the <b>domain</b> starting with a period, like this: .your-domain.com

The path is usually not needed since the function sets a root path.

The prefix is only needed if you need to avoid name collisions with other identically named cookies for your server.

<b>Discrete Parameters</b>

If you prefer, you can set the cookie by passing data using individual parameters:
$this->cookie->set($name, $value, $expire, $domain, $path, $prefix);

#### $this->cookie->get()

Lets you fetch a cookie. The first parameter will contain the name of the cookie you are looking for (including any prefixes):

```php
$this->cookie->get('some_cookie');
```

The function returns false (boolean) if the item you are attempting to retrieve does not exist.

The second optional parameter lets you run the data through the XSS filter. It's enabled by setting the second parameter to boolean true;

```php
$this->cookie->get('some_cookie', true);
```

#### $this->cookie->delete()

Lets you delete a cookie. Unless you've set a custom path or other values, only the name of the cookie is needed:

```php
$this->cookie->delete("name");
```

This function is otherwise identical to <kbd>$this->cookie->set()</kbd>, except that it does not have the value and expiration parameters. You can submit an array of values in the first parameter or you can set discrete parameters.

```php
$this->cookie->delete($name, $domain, $path, $prefix)
```