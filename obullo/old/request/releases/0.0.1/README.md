## Request Class

Request class detects the server request method, secure connection, ip address, ajax requests and other similar things.

### Initializing the Class

------

```php
new Request;
$this->request->method();
```

Once loaded, the Request object will be available using: <dfn>$this->request->method()</dfn>

#### $this->request->getMethod();

if its available returns to http method otherwise false.

#### $this->request->getHeader($key);

Fetches the http server header.

```php
echo $this->request->getHeader('host'); // demo_blog
echo $this->request->getHeader('content-type'); // gzip, deflate
echo $this->request->getHeader('connection'); // keep-alive
```

#### $this->request->getIpAddress();

Returns the IP address for the current user. If the IP address is not valid, the function will return an IP of: 0.0.0.0

```php
echo $this->get->ipAddress();  // 216.185.81.90
```

#### $this->request->isValidIp($ip);

Gets the IP address as input and returns true or false (boolean) depending on it is valid or not. 

***Note:*** The $this->request->getIpAddress() method also validates the IP automatically.

```php
if ( ! $this->request->getValidIp($ip))
{
     echo 'Not Valid';
}
else
{
     echo 'Valid';
}
```

#### $this->request->isXmlHttp($ip);

Returns "true" if xmlHttpRequest ( Ajax ) available in server header.

#### $this->request->isSecure();

Returns "true" if the secure connection ( Https ) available in server header.

#### $this->request->isHvc();

Returns "true" if request is Hvc ( Hierarchical View Controller ).
