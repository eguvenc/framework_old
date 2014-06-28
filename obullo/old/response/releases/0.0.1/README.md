## Response Class

The Response class is a small class with one main function: To send the finalized web page to the requesting browser. It is also responsible for caching your web pages, if you use that feature.

**Note:** This class is initialized automatically by the system so there is no need to do it manually.

**Note:** This class is a <kbd>component</kbd> defined in your package.json. You can <kbd>replace components</kbd> with third-party packages.

Under normal circumstances you won't even notice the <b>Response</b> class since it works transparently without your intervention. For example, when you use the <kbd>new keyword</kbd> to load a view file, it's automatically passed to the <b>Reponse</b> class, which will be called automatically by Obullo at the end of system execution.

It is possible, however, for you to manually intervene with the <b>Reponse</b> if you need to, using either of the two following functions:

Look at to <kbd>/docs/advanced/caching-and-compression</kbd> pages for for details.

#### $this->response->setOutput();

Permits you to manually set the final output string. Usage example:

```php
$this->response->setOutput($data);
```

**Important:** If you do set your output manually, it must be the last thing done in the function you call it from. For example, if you build a page in one of your controller functions, don't set the output until the end.

#### $this->response->getOutput();

Permits you to manually retrieve any output that has been sent for storage in the output class. Usage example:

```php
$string = $this->response->getOutput();
```

Note that data will only be retrievable from this function if it has been previously sent to the output class by one of the <kbd>vi</kbd> package functions like <kbd>view()</kbd>.

#### $this->response->setHeader();

Permits you to manually set server headers, which the output class will send for you when outputting the final rendered display. Example:

```php
$this->reponse->setHeader("HTTP/1.0 200 OK");
$this->reponse->setHeader("HTTP/1.1 200 OK");
$this->reponse->setHeader('Last-Modified: '.gmdate('D, d M Y H:i:s', $last_update).' GMT');
$this->reponse->setHeader("Cache-Control: no-store, no-cache, must-revalidate");
$this->reponse->setHeader("Cache-Control: post-check=0, pre-check=0");
$this->reponse->setHeader("Pragma: no-cache"); 
```

#### $this->reponse->setHttpResponse(code, 'text');

Permits you to manually set a server status header. Example:

```php
$this->reponse->setHttpResponse('401');  // Sets the header as:  Unauthorized
```

[See here](http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html) for a full list of headers.


### Error Functions and Headers

------

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